<?php
class BloodRequest {
    private $conn;
    private $table = 'blood_requests';

    public function __construct($db) {
        $this->conn = $db;
    }

    #Post Emergency SOS or Hospital Requirement
    public function createRequest($userId, $patientName, $bloodGroup, $reqType, $urgency, $units, $location, $lat, $lng, $hospital, $contact, $medDetails, $isAnon) {
        $sql = "INSERT INTO " . $this->table . " 
                (user_id, patient_name, blood_group, requirement_type, urgency_level, units_required, location, latitude, longitude, hospital_name, contact_number, medical_details, is_anonymous) 
                VALUES (:user_id, :patient_name, :blood_group, :req_type, :urgency, :units, :location, :lat, :lng, :hospital, :contact, :med_details, :is_anon)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':patient_name', $patientName);
        $stmt->bindParam(':blood_group', $bloodGroup);
        $stmt->bindParam(':req_type', $reqType);
        $stmt->bindParam(':urgency', $urgency);
        $stmt->bindParam(':units', $units);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':lat', $lat);
        $stmt->bindParam(':lng', $lng);
        $stmt->bindParam(':hospital', $hospital);
        $stmt->bindParam(':contact', $contact);
        $stmt->bindParam(':med_details', $medDetails);
        $stmt->bindParam(':is_anon', $isAnon, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }
    #Active Real-time SOS Alerts
    public function getActiveSOSBroadcasts() {
        $sql = "SELECT r.*, u.full_name as poster_name, u.anonymous_mode 
                FROM " . $this->table . " r 
                JOIN users u ON r.user_id = u.id 
                WHERE r.urgency_level = 'Emergency_SOS' AND r.status = 'Active' 
                ORDER BY r.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    #Fetch single request detail
    public function getById($id) {
        $sql = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }
}