<?php
require_once __DIR__ . '/../config/Database.php';

class DonationHistory {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getHistoryByUser($userId) {
        $stmt = $this->db->prepare("SELECT * FROM donations WHERE donor_id = :user_id ORDER BY donation_date DESC");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function recordFollowup($userId, $donationId, $sideEffects, $wellbeingScore) {
        $stmt = $this->db->prepare("INSERT INTO donation_followups (donor_id, donation_id, side_effects, wellbeing_score, created_at) 
                                    VALUES (:donor_id, :donation_id, :side_effects, :wellbeing_score, NOW())");
        return $stmt->execute([
            'donor_id' => $userId,
            'donation_id' => $donationId,
            'side_effects' => $sideEffects,
            'wellbeing_score' => $wellbeingScore
        ]);
    }
}
