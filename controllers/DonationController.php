<?php

require_once __DIR__ . '/../models/BloodRequestModel.php';
require_once __DIR__ . '/../models/DonationModel.php';
require_once __DIR__ . '/../models/UserModel.php';

class DonationController extends Controller {

    /**
     * Show the "Accept & Donate" confirmation form.
     * Only accessible by donors whose blood group matches the request.
     */
    public function accept(): void {
        $this->requireRole(['donor']);

        $requestId = filter_input(INPUT_GET, 'request_id', FILTER_VALIDATE_INT);
        if (!$requestId) {
            header('Location: /smart_blood_network/requests/list');
            exit;
        }

        $reqModel = new BloodRequestModel();
        $request  = $reqModel->getById($requestId);

        if (!$request || $request['status'] !== 'Active') {
            header('Location: /smart_blood_network/requests/list');
            exit;
        }

        if (($_SESSION['blood_group'] ?? '') !== $request['blood_group']) {
            header('Location: /smart_blood_network/requests/list');
            exit;
        }

        $inCooldown = (($_SESSION['user_status'] ?? 'Available') === 'Resting');

        $this->render('donations/accept', [
            'request'    => $request,
            'inCooldown' => $inCooldown,
        ]);
    }

    /**
     * Process the donation: write record, set donor to Resting, update session.
     */
    public function save(): void {
        $this->requireRole(['donor']);

        $donorId   = (int)$_SESSION['user_id'];
        $requestId = filter_input(INPUT_POST, 'request_id', FILTER_VALIDATE_INT);

        $reqModel = new BloodRequestModel();
        $request  = $reqModel->getById((int)$requestId);

        if (!$request || ($_SESSION['blood_group'] ?? '') !== $request['blood_group']) {
            header('Location: /smart_blood_network/requests/list');
            exit;
        }

        if (($_SESSION['user_status'] ?? '') === 'Resting') {
            header('Location: /smart_blood_network/donations/history');
            exit;
        }

        $units = max(1, (int)filter_input(INPUT_POST, 'units_donated', FILTER_VALIDATE_INT));

        $donationModel = new DonationModel();
        $donationModel->create([
            'donor_id'      => $donorId,
            'request_id'    => $requestId,
            'blood_group'   => $_SESSION['blood_group'],
            'units_donated' => $units,
            'donation_date' => date('Y-m-d'),
            'hospital_name' => $request['hospital_name'],
        ]);

        (new UserModel())->updateStatus($donorId, 'Resting');
        $_SESSION['user_status'] = 'Resting';

        $_SESSION['flash_success'] = '🩸 Thank you! Your donation has been recorded. You are now in a 90-day rest period.';
        header('Location: /smart_blood_network/donations/history');
        exit;
    }

    /**
     * Donor's full donation history with badge level and cooldown countdown.
     */
    public function history(): void {
        $this->requireRole(['donor']);

        $donorId       = (int)$_SESSION['user_id'];
        $donationModel = new DonationModel();

        $donations = $donationModel->getByDonor($donorId);
        $count     = $donationModel->countByDonor($donorId);
        $lastDate  = $donationModel->getLastDonationDate($donorId);

        $badge = match(true) {
            $count >= 10 => ['name' => 'Platinum', 'color' => 'info',    'icon' => '💎'],
            $count >= 6  => ['name' => 'Gold',     'color' => 'warning', 'icon' => '🥇'],
            $count >= 3  => ['name' => 'Silver',   'color' => 'secondary','icon'=> '🥈'],
            $count >= 1  => ['name' => 'Bronze',   'color' => 'danger',  'icon' => '🥉'],
            default      => ['name' => 'None',     'color' => 'light',   'icon' => '—'],
        };

        $cooldownDaysLeft = null;
        if ($lastDate && ($_SESSION['user_status'] ?? '') === 'Resting') {
            $cooldownEnd = (new DateTime($lastDate))->modify('+90 days');
            $today       = new DateTime();
            if ($cooldownEnd > $today) {
                $cooldownDaysLeft = (int)$today->diff($cooldownEnd)->days;
            } else {
                (new UserModel())->updateStatus($donorId, 'Available');
                $_SESSION['user_status'] = 'Available';
            }
        }

        $this->render('donations/history', [
            'donations'        => $donations,
            'count'            => $count,
            'badge'            => $badge,
            'cooldownDaysLeft' => $cooldownDaysLeft,
            'lastDate'         => $lastDate,
        ]);
    }
}
