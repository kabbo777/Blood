<?php
require_once __DIR__ . '/../config/Database.php';

class DonationHistory {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function getHistoryByDonor($donorId) {
        $query = "SELECT * FROM donations WHERE donor_id = :donor_id ORDER BY donation_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':donor_id', $donorId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function recordDonation($donorId, $bloodGroup, $district, $hospitalName, $donationDate) {
        $certCode = 'SBN-CERT-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
        $query = "INSERT INTO donations (donor_id, blood_group, district, hospital_name, donation_date, certificate_code) 
                  VALUES (:donor_id, :blood_group, :district, :hospital_name, :donation_date, :cert_code)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':donor_id', $donorId);
        $stmt->bindParam(':blood_group', $bloodGroup);
        $stmt->bindParam(':district', $district);
        $stmt->bindParam(':hospital_name', $hospitalName);
        $stmt->bindParam(':donation_date', $donationDate);
        $stmt->bindParam(':cert_code', $certCode);
        return $stmt->execute() ? $certCode : false;
    }
}