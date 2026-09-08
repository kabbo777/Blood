<?php
class LeaderboardModel {
    private $db;

    public function __construct($db = null) {
        if ($db !== null) {
            $this->db = $db;
        } elseif (isset($GLOBALS['db']) && $GLOBALS['db'] instanceof PDO) {
            $this->db = $GLOBALS['db'];
        } elseif (isset($GLOBALS['pdo']) && $GLOBALS['pdo'] instanceof PDO) {
            $this->db = $GLOBALS['pdo'];
        } elseif (isset($GLOBALS['conn']) && $GLOBALS['conn'] instanceof PDO) {
            $this->db = $GLOBALS['conn'];
        } else {
            $config_files = [
                __DIR__ . '/../config/database.php',
                __DIR__ . '/../config/db.php',
                __DIR__ . '/../config.php',
                __DIR__ . '/../db.php'
            ];
            foreach ($config_files as $file) {
                if (file_exists($file)) {
                    require_once $file;
                    break;
                }
            }

            if (isset($pdo) && $pdo instanceof PDO) {
                $this->db = $pdo;
            } elseif (isset($db) && $db instanceof PDO) {
                $this->db = $db;
            } elseif (isset($conn) && $conn instanceof PDO) {
                $this->db = $conn;
            } else {
                try {
                    $this->db = new PDO("mysql:host=localhost;dbname=smart_blood_db;charset=utf8mb4", "root", "", [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]);
                } catch (PDOException $e) {
                    die("Database connection failed: " . $e->getMessage());
                }
            }
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
