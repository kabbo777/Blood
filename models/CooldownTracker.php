<?php
require_once __DIR__ . '/../config/Database.php';

class CooldownTracker {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getCooldownStatus($userId) {
        $stmt = $this->db->prepare("SELECT MAX(donation_date) as last_donation FROM donations WHERE donor_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        $row = $stmt->fetch();

        if (!$row || !$row['last_donation']) {
            return ['eligible' => true, 'days_remaining' => 0];
        }

        $lastDonationDate = new DateTime($row['last_donation']);
        $nextEligibleDate = (clone $lastDonationDate)->modify('+90 days');
        $today = new DateTime();

        if ($today >= $nextEligibleDate) {
            return ['eligible' => true, 'days_remaining' => 0];
        }

        $interval = $today->diff($nextEligibleDate);
        return [
            'eligible' => false,
            'days_remaining' => $interval->days,
            'next_eligible_date' => $nextEligibleDate->format('Y-m-d')
        ];
    }
}
