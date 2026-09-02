<?php
require_once __DIR__ . '/../config/Database.php';

class CertificateController {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function getCertificateData($code) {
        $query = "SELECT d.*, u.name as donor_name 
                  FROM donations d 
                  JOIN users u ON d.donor_id = u.id 
                  WHERE d.certificate_code = :code LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':code', $code);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}