<?php

require_once __DIR__ . '/../config/Database.php';

class BloodRequestModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    
    public function createRequest(
        int    $userId,
        string $patientName,
        string $bloodGroup,
        string $requirementType,
        int    $unitsRequired,
        string $hospitalName,
        string $location,
        string $urgency,
        bool   $isAnonymous,
        string $contactNumber,
        string $medicalDetails = ''
    ): int {
        $stmt = $this->db->prepare(
            "INSERT INTO `blood_requests`
                (`user_id`,`patient_name`,`blood_group`,`requirement_type`,
                 `units_required`,`hospital_name`,`location`,`urgency_level`,
                 `is_anonymous`,`contact_number`,`medical_details`,`status`)
             VALUES
                (:uid,:pname,:bg,:req_type,
                 :units,:hname,:loc,:urgency,
                 :anon,:contact,:details,'Active')"
        );
        $stmt->execute([
            'uid'      => $userId,
            'pname'    => $patientName,
            'bg'       => $bloodGroup,
            'req_type' => $requirementType,
            'units'    => $unitsRequired,
            'hname'    => $hospitalName,
            'loc'      => $location,
            'urgency'  => $urgency,
            'anon'     => $isAnonymous ? 1 : 0,
            'contact'  => $contactNumber,
            'details'  => $medicalDetails,
        ]);

        $requestId = (int) $this->db->lastInsertId();

       
        if (in_array($urgency, ['Emergency_SOS', 'High'])) {
            $this->broadcastSOS($requestId, $bloodGroup, $location, $hospitalName);
        }

        return $requestId;
    }

    
    private function broadcastSOS(int $requestId, string $bloodGroup, string $location, string $hospitalName): void {
        $stmt = $this->db->prepare(
            "SELECT `id` FROM `users`
             WHERE `role` = 'donor'
               AND `blood_group` = :bg
               AND `district`    = :loc
               AND `status`      = 'Available'"
        );
        $stmt->execute(['bg' => $bloodGroup, 'loc' => $location]);
        $donors = $stmt->fetchAll();

        if (empty($donors)) return;

        $msg      = "🚨 URGENT SOS: Blood group {$bloodGroup} needed at {$hospitalName} ({$location}). Please respond!";
        $notifStmt = $this->db->prepare(
            "INSERT INTO `notifications` (`user_id`,`message`,`type`) VALUES (:uid,:msg,'sos')"
        );
        foreach ($donors as $donor) {
            $notifStmt->execute(['uid' => $donor['id'], 'msg' => $msg]);
        }
    }

    
    public function getAllRequests(): array {
        $stmt = $this->db->prepare(
            "SELECT * FROM `blood_requests` ORDER BY `created_at` DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    
    public function getById(int $id): array|false {
        $stmt = $this->db->prepare("SELECT * FROM `blood_requests` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
