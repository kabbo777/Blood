<?php
class HealthScreener {
    private $conn;
    private $table = 'health_screenings';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function evaluateAndSave($userId, $age, $weight, $hasChronic, $recentTattoo, $feelingHealthy) {
        $reasons = [];

        if ($age < 18 || $age > 65) {
            $reasons[] = 'Age must be between 18 and 65 years old.';
        }
        if ($weight < 50.0) {
            $reasons[] = 'Weight must be at least 50 kg.';
        }
        if ($hasChronic) {
            $reasons[] = 'Chronic medical illnesses require medical clearance.';
        }
        if ($recentTattoo) {
            $reasons[] = 'Recent tattoo, piercing, or major surgery requires a 6-month wait period.';
        }
        if (!$feelingHealthy) {
            $reasons[] = 'Must feel fully healthy and well on the day of donation.';
        }

        $isEligible = empty($reasons) ? 1 : 0;

        $sql = "INSERT INTO " . $this->table . " 
                (user_id, age, weight_kg, has_chronic_illness, recent_tattoo_surgery, feeling_healthy, is_eligible) 
                VALUES (:user_id, :age, :weight, :chronic, :tattoo, :healthy, :eligible)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':age', $age);
        $stmt->bindParam(':weight', $weight);
        $stmt->bindParam(':chronic', $hasChronic, PDO::PARAM_INT);
        $stmt->bindParam(':tattoo', $recentTattoo, PDO::PARAM_INT);
        $stmt->bindParam(':healthy', $feelingHealthy, PDO::PARAM_INT);
        $stmt->bindParam(':eligible', $isEligible, PDO::PARAM_INT);
        $stmt->execute();

        return [
            'eligible' => (bool)$isEligible,
            'reasons' => $reasons
        ];
    }

    public function getLatestScreening($userId) {
        $sql = "SELECT * FROM " . $this->table . " WHERE user_id = :user_id ORDER BY id DESC LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? $result : null;
    }
}