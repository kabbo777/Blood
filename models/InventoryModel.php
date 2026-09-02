<?php
require_once __DIR__ . '/../config/Database.php';

class InventoryModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function addInventory($hospitalId, $bloodGroup, $units, $expiryDate) {
        $query = "INSERT INTO blood_inventory (hospital_id, blood_group, units, expiry_date) 
                  VALUES (:hospital_id, :blood_group, :units, :expiry_date)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':hospital_id', $hospitalId);
        $stmt->bindParam(':blood_group', $bloodGroup);
        $stmt->bindParam(':units', $units);
        $stmt->bindParam(':expiry_date', $expiryDate);
        return $stmt->execute();
    }

    public function getInventoryByHospital($hospitalId) {
        $query = "SELECT *, 
                  CASE 
                    WHEN expiry_date < CURDATE() THEN 'Expired'
                    WHEN expiry_date <= DATE_ADD(CURDATE(), INTERVAL 7 DAY) THEN 'Expiring Soon'
                    ELSE 'Valid'
                  END as expiry_status 
                  FROM blood_inventory 
                  WHERE hospital_id = :hospital_id 
                  ORDER BY expiry_date ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':hospital_id', $hospitalId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}