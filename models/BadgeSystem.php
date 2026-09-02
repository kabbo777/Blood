<?php
require_once __DIR__ . '/../config/Database.php';

class BadgeSystem {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function getDonorBadges($donorId) {
        $query = "SELECT COUNT(*) as total FROM donations WHERE donor_id = :donor_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':donor_id', $donorId);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $count = $row['total'] ?? 0;

        $badges = [];
        if ($count >= 1) {
            $badges[] = ['title' => 'First Blood', 'level' => 'Bronze', 'desc' => 'Completed 1st successful donation'];
        }
        if ($count >= 3) {
            $badges[] = ['title' => 'Life Saver', 'level' => 'Silver', 'desc' => 'Completed 3+ donations'];
        }
        if ($count >= 5) {
            $badges[] = ['title' => 'Community Hero', 'level' => 'Gold', 'desc' => 'Completed 5+ donations'];
        }
        if ($count >= 10) {
            $badges[] = ['title' => 'Legendary Guardian', 'level' => 'Platinum', 'desc' => 'Completed 10+ donations'];
        }

        return ['count' => $count, 'badges' => $badges];
    }
}