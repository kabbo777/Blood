<?php
require_once __DIR__ . '/../config/Database.php';

class LeaderboardModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function getDistrictLeaderboard($district = '') {
        $query = "SELECT u.name, d.district, d.blood_group, COUNT(d.id) as donation_count 
                  FROM donations d 
                  JOIN users u ON d.donor_id = u.id ";
        if (!empty($district)) {
            $query .= " WHERE d.district = :district ";
        }
        $query .= " GROUP BY d.donor_id, d.district ORDER BY donation_count DESC LIMIT 20";
        
        $stmt = $this->conn->prepare($query);
        if (!empty($district)) {
            $stmt->bindParam(':district', $district);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}