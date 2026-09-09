<?php
require_once __DIR__ . '/../models/BadgeSystem.php';
require_once __DIR__ . '/../models/DonationHistory.php';
require_once __DIR__ . '/../models/CooldownTracker.php';
require_once __DIR__ . '/../models/UserModel.php';

class DonorController extends Controller {
    public function badges() {
        $this->requireRole(['donor']);

        $badgeSystem = new BadgeSystem();
        $badgeData   = $badgeSystem->getUserBadges($_SESSION['user_id']);

        $this->render('donor/badges', ['badgeData' => $badgeData]);
    }

    public function history() {
        $this->requireRole(['donor']);

        $historyModel = new DonationHistory();
        $cooldown     = new CooldownTracker();

        $history        = $historyModel->getHistoryByUser($_SESSION['user_id']);
        $cooldownStatus = $cooldown->getCooldownStatus($_SESSION['user_id']);

        $this->render('donor/history_tips', [
            'history'  => $history,
            'cooldown' => $cooldownStatus
        ]);
    }

    public function toggleStatus() {
        $this->requireRole(['donor']);
        $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_SPECIAL_CHARS);
        
        if (in_array($status, ['Available', 'Resting', 'Unavailable'])) {
            $userModel = new UserModel();
            $userModel->updateStatus($_SESSION['user_id'], $status);
            $_SESSION['user_status'] = $status;
        }
        
        header("Location: /smart_blood_network/home");
        exit();
    }

    public function followup() {
        $this->requireRole(['donor']);
        $this->render('donor/followup');
    }

    public function saveFollowup() {
        $this->requireRole(['donor']);

        $donationId     = filter_input(INPUT_POST, 'donation_id', FILTER_VALIDATE_INT);
        $sideEffects    = filter_input(INPUT_POST, 'side_effects', FILTER_SANITIZE_SPECIAL_CHARS);
        $wellbeingScore = filter_input(INPUT_POST, 'wellbeing_score', FILTER_VALIDATE_INT);

        if ($donationId && $wellbeingScore) {
            $historyModel = new DonationHistory();
            $historyModel->recordFollowup($_SESSION['user_id'], $donationId, $sideEffects, $wellbeingScore);
        }

        header("Location: /smart_blood_network/donor/history");
        exit();
    }
}
