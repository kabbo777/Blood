<?php
require_once __DIR__ . '/../config/Database.php';

class DonorMatching {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findMatches($bloodGroup, $lat, $lng, $maxDistanceKm = 50) {
        $sql = "SELECT u.id, u.name, u.phone, u.latitude, u.longitude,
                (6371 * acos(cos(radians(:lat)) * cos(radians(u.latitude)) * 
                cos(radians(u.longitude) - radians(:lng)) + sin(radians(:lat)) * 
                sin(radians(u.latitude)))) AS distance
                FROM users u
                WHERE u.role = 'donor' 
                  AND u.blood_group = :blood_group
                  AND u.status = 'active'
                  AND u.id NOT IN (
                      SELECT donor_id FROM donations 
                      WHERE donation_date > DATE_SUB(NOW(), INTERVAL 90 DAY)
                  )
                HAVING distance <= :max_distance
                ORDER BY distance ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'lat' => $lat,
            'lng' => $lng,
            'blood_group' => $bloodGroup,
            'max_distance' => $maxDistanceKm
        ]);

        return $stmt->fetchAll();
    }
}
