<?php
require_once __DIR__ . '/../models/BadgeSystem.php';
require_once __DIR__ . '/../models/DonationHistory.php';
require_once __DIR__ . '/../models/CooldownTracker.php';

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

        header("Location: /donor/history");
        exit();
    }
}