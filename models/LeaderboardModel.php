<?php
class LeaderboardModel {
    private $db;

    public function __construct($db = null) {
        if ($db !== null) {
            $this->db = $db;
        } elseif (isset($GLOBALS['db'])) {
            $this->db = $GLOBALS['db'];
        } elseif (isset($GLOBALS['pdo'])) {
            $this->db = $GLOBALS['pdo'];
        }
    }

    public function getDistrictLeaderboard($district = "") {
        $sql = "SELECT u.id, u.full_name AS name, u.blood_group, u.location, u.reliability_score, COUNT(d.id) AS total_donations 
                FROM users u 
                LEFT JOIN donations d ON u.id = d.donor_id 
                WHERE u.role = 'donor'";
        
        $params = [];
        if (!empty($district)) {
            $sql .= " AND (u.location = :district OR d.district = :district)";
            $params[':district'] = $district;
        }

        $sql .= " GROUP BY u.id 
                 ORDER BY total_donations DESC, u.reliability_score DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTopDonors($limit = 10) {
        $sql = "SELECT u.id, u.full_name AS name, u.blood_group, u.location, u.reliability_score, COUNT(d.id) AS total_donations 
                FROM users u 
                LEFT JOIN donations d ON u.id = d.donor_id 
                WHERE u.role = 'donor' 
                GROUP BY u.id 
                ORDER BY total_donations DESC, u.reliability_score DESC 
                LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
