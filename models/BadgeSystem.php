<?php
require_once __DIR__ . '/../config/Database.php';

class BadgeSystem {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getUserBadges($userId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM donations WHERE donor_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        $count = $stmt->fetch()['total'] ?? 0;

        $badges = [];
        if ($count >= 1)  $badges[] = ['tier' => 'Bronze', 'description' => 'First Donation Completed'];
        if ($count >= 5)  $badges[] = ['tier' => 'Silver', 'description' => '5 Donations Completed'];
        if ($count >= 10) $badges[] = ['tier' => 'Gold', 'description' => '10 Donations Completed'];
        if ($count >= 25) $badges[] = ['tier' => 'Platinum', 'description' => 'Lifesaver Legend'];

        return ['total_donations' => $count, 'badges' => $badges];
    }
}
