<?php
require_once __DIR__ . '/../config/Database.php';

class InventoryModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getInventoryByHospital($hospitalId) {
        $stmt = $this->db->prepare("SELECT * FROM blood_inventory WHERE hospital_id = :hospital_id ORDER BY expiry_date ASC");
        $stmt->execute(['hospital_id' => $hospitalId]);
        return $stmt->fetchAll();
    }

    public function updateUnits($inventoryId, $units) {
        $stmt = $this->db->prepare("UPDATE blood_inventory SET units = :units WHERE id = :id");
        return $stmt->execute(['units' => $units, 'id' => $inventoryId]);
    }
}
