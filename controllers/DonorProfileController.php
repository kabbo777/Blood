<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/CooldownTracker.php';

class DonorProfileController {
    private $db;
    private $userModel;
    private $cooldownModel;

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
        $this->userModel = new User($this->db);
        $this->cooldownModel = new CooldownTracker($this->db);
    }

    public function updateLocation() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_location'])) {
            $location = trim($_POST['location'] ?? 'Dhaka');
            $lat = (float)($_POST['latitude'] ?? 23.8103);
            $lng = (float)($_POST['longitude'] ?? 90.4125);
            $userId = $_SESSION['user_id'];

            if ($this->userModel->updateLocation($userId, $location, $lat, $lng)) {
                return ['success' => 'Location and GPS coordinates updated successfully. Your position is now live on the map.'];
            }
            return ['error' => 'Failed to update location coordinates.'];
        }
    }

    public function updateAvailability() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_availability'])) {
            $status = $_POST['availability_status'] ?? '';
            $userId = $_SESSION['user_id'];

            if ($this->userModel->updateAvailability($userId, $status)) {
                return ['success' => 'Availability status updated successfully.'];
            }
            return ['error' => 'Failed to update availability.'];
        }
    }

    public function toggleAnonymousMode() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_privacy'])) {
            $isAnonymous = isset($_POST['anonymous_mode']) && $_POST['anonymous_mode'] == '1';
            $userId = $_SESSION['user_id'];

            if ($this->userModel->toggleAnonymousMode($userId, $isAnonymous)) {
                return ['success' => 'Privacy setting updated successfully.'];
            }
            return ['error' => 'Failed to update privacy settings.'];
        }
    }

    public function getProfileData() {
        $userId = $_SESSION['user_id'];
        $profile = $this->userModel->getById($userId);
        $cooldown = $this->cooldownModel->getCooldownStatus($userId);

        if (!empty($cooldown['is_in_cooldown']) && ($profile['availability_status'] ?? '') === 'Available') {
            $this->userModel->updateAvailability($userId, 'Resting');
            $profile['availability_status'] = 'Resting';
        }

        return [
            'profile' => $profile,
            'cooldown' => $cooldown
        ];
    }
}