<?php
class CooldownTracker {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getCooldownStatus($userId) {
        $sql = "SELECT MAX(donation_date) as last_donation FROM donation_records WHERE donor_id = :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $lastDate = $row['last_donation'] ?? null;

        if (!$lastDate) {
            return [
                'last_donation_date' => null,
                'is_in_cooldown' => false,
                'days_passed' => 0,
                'days_remaining' => 0
            ];
        }

        $lastDateTime = new DateTime($lastDate);
        $today = new DateTime();
        $diffDays = $today->diff($lastDateTime)->days;

        $cooldownLimit = 90;
        $isInCooldown = $diffDays < $cooldownLimit;
        $daysRemaining = $isInCooldown ? ($cooldownLimit - $diffDays) : 0;

        return [
            'last_donation_date' => $lastDate,
            'is_in_cooldown' => $isInCooldown,
            'days_passed' => $diffDays,
            'days_remaining' => $daysRemaining
        ];
    }
}