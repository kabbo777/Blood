<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/HealthScreener.php';
require_once __DIR__ . '/../models/User.php';

class ScreenerController {
    private $db;
    private $screenerModel;
    private $userModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: http://localhost/smart_blood_network/views/auth/login.php?msg=login_required');
            exit();
        }

        $database = new Database();
        $this->db = $database->connect();
        $this->screenerModel = new HealthScreener($this->db);
        $this->userModel = new User($this->db);
    }

    public function submitScreener() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $bloodGroup = $_POST['blood_group'] ?? null;
            $age = (int)($_POST['age'] ?? 0);
            $weight = (float)($_POST['weight_kg'] ?? 0);
            $hasChronic = isset($_POST['has_chronic_illness']) ? 1 : 0;
            $recentTattoo = isset($_POST['recent_tattoo_surgery']) ? 1 : 0;
            $feelingHealthy = isset($_POST['feeling_healthy']) ? 1 : 0;

            // Update blood group in user profile if submitted
            if ($bloodGroup) {
                $this->userModel->updateBloodGroup($userId, $bloodGroup);
            }

            $evalResult = $this->screenerModel->evaluateAndSave(
                $userId, $age, $weight, $hasChronic, $recentTattoo, $feelingHealthy
            );

            return [
                'processed' => true,
                'is_eligible' => $evalResult['eligible'],
                'reasons' => $evalResult['reasons'],
                'blood_group_updated' => true
            ];
        }
    }

    public function getLastStatus() {
        return $this->screenerModel->getLatestScreening($_SESSION['user_id']);
    }

    public function getCurrentUserBloodGroup() {
        $user = $this->userModel->getById($_SESSION['user_id']);
        return $user['blood_group'] ?? 'O+';
    }
}