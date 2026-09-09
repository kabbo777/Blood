<?php

require_once __DIR__ . '/../config/Database.php';

class InventoryModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    
    public function getAllInventory(): array {
        $stmt = $this->db->prepare(
            "SELECT bi.*, h.name AS hospital_name
             FROM `blood_inventory` bi
             LEFT JOIN `hospitals_blood_banks` h ON bi.hospital_id = h.id
             ORDER BY bi.expiry_date ASC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }


    public function getInventoryByHospital(int $hospitalId): array {
        $stmt = $this->db->prepare(
            "SELECT * FROM `blood_inventory` WHERE `hospital_id` = ? ORDER BY `expiry_date` ASC"
        );
        $stmt->execute([$hospitalId]);
        return $stmt->fetchAll();
    }

    public function updateUnits(int $inventoryId, int $units): bool {
        $stmt = $this->db->prepare(
            "UPDATE `blood_inventory` SET `units` = ? WHERE `id` = ?"
        );
        return $stmt->execute([$units, $inventoryId]);
    }

    public function addInventory(int $hospitalId, string $bloodGroup, int $units, string $expiryDate): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO `blood_inventory` (`hospital_id`,`blood_group`,`units`,`expiry_date`,`status`)
             VALUES (?,?,?,?,'Available')"
        );
        return $stmt->execute([$hospitalId, $bloodGroup, $units, $expiryDate]);
    }
}
