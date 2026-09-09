<?php
require_once __DIR__ . '/../config/Database.php';

class DonorMatching {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findMatchesWithScoring($bloodGroup, $lat, $lng, $maxDistanceKm = 50) {
        $sql = "SELECT u.id, u.name, u.phone, u.blood_group, u.latitude, u.longitude, u.status,
                (6371 * acos(cos(radians(:lat)) * cos(radians(u.latitude)) * 
                cos(radians(u.longitude) - radians(:lng)) + sin(radians(:lat)) * 
                sin(radians(u.latitude)))) AS distance,
                (SELECT COUNT(*) FROM donations d WHERE d.donor_id = u.id) as completed_donations
                FROM users u
                WHERE u.role = 'donor' 
                  AND u.status = 'Available'
                HAVING distance <= :max_distance
                ORDER BY distance ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'lat' => $lat,
            'lng' => $lng,
            'max_distance' => $maxDistanceKm
        ]);

        $donors = $stmt->fetchAll();

        foreach ($donors as &$donor) {
            $compatibilityScore = ($donor['blood_group'] === $bloodGroup) ? 50 : 30;
            $proximityScore     = max(0, 30 - ($donor['distance'] * 0.6));
            $reliabilityScore   = min(20, $donor['completed_donations'] * 4);
            
            $donor['match_score'] = round($compatibilityScore + $proximityScore + $reliabilityScore);
        }

        usort($donors, function($a, $b) {
            return $b['match_score'] <=> $a['match_score'];
        });

        return $donors;
    }
}
