<?php

require_once __DIR__ . '/../config/Database.php';

class DonorModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    
    public function searchDonors(string $bloodGroup): array {
        $stmt = $this->db->prepare(
            "SELECT `id`,`name`,`blood_group`,`latitude`,`longitude`,`phone`
             FROM `users`
             WHERE `role` = 'donor'
               AND `blood_group` = ?
               AND `health_check_passed` = 1"
        );
        $stmt->execute([$bloodGroup]);
        return $stmt->fetchAll();
    }

    public function getAllVerifiedDonors(): array {
        $stmt = $this->db->prepare(
            "SELECT `id`,`name`,`blood_group`,`latitude`,`longitude`
             FROM `users`
             WHERE `role` = 'donor'
               AND `health_check_passed` = 1
               AND `latitude` IS NOT NULL"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
