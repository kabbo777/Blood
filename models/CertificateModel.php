<?php
require_once __DIR__ . '/../config/Database.php';

class CertificateModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getCertificateData($userId, $donationId) {
        $stmt = $this->db->prepare("SELECT d.*, u.name as donor_name, u.blood_group 
                                    FROM donations d 
                                    JOIN users u ON d.donor_id = u.id 
                                    WHERE d.id = :donation_id AND d.donor_id = :user_id");
        $stmt->execute(['donation_id' => $donationId, 'user_id' => $userId]);
        return $stmt->fetch();
    }
}