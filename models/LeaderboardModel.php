<?php
require_once __DIR__ . '/../config/Database.php';

class LeaderboardModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getLeaderboardByDistrict($district) {
        $sql = "SELECT u.name, u.district, COUNT(d.id) AS total_donations
                FROM users u
                JOIN donations d ON u.id = d.donor_id
                WHERE u.district = :district
                GROUP BY u.id
                ORDER BY total_donations DESC
                LIMIT 20";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['district' => $district]);
        return $stmt->fetchAll();
    }
}
