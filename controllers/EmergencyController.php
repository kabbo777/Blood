<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/BloodRequest.php';

class EmergencyController {
    private $db;
    private $requestModel;

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
        $this->requestModel = new BloodRequest($this->db);
    }

    public function handleCreateRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $patientName = trim($_POST['patient_name'] ?? '');
            $bloodGroup = $_POST['blood_group'] ?? 'O+';
            $reqType = $_POST['requirement_type'] ?? 'Blood';
            $urgency = $_POST['urgency_level'] ?? 'High';
            $units = (int)($_POST['units_required'] ?? 1);
            $location = trim($_POST['location'] ?? 'Dhaka');
            $lat = (float)($_POST['latitude'] ?? 23.8103);
            $lng = (float)($_POST['longitude'] ?? 90.4125);
            $hospital = trim($_POST['hospital_name'] ?? '');
            $contact = trim($_POST['contact_number'] ?? '');
            $medDetails = trim($_POST['medical_details'] ?? '');
            $isAnon = isset($_POST['is_anonymous']) ? 1 : 0;

            $requestId = $this->requestModel->createRequest(
                $userId, $patientName, $bloodGroup, $reqType, $urgency, 
                $units, $location, $lat, $lng, $hospital, $contact, $medDetails, $isAnon
            );

            if ($requestId) {
                if ($urgency === 'Emergency_SOS') {
                    header('Location: sos_broadcast.php?success=1');
                } else {
                    header('Location: ../matching/ai_matches.php?request_id=' . $requestId);
                }
                exit();
            }
            return ['error' => 'Failed to publish blood request.'];
        }
    }

    public function getSOSBroadcasts() {
        return $this->requestModel->getActiveSOSBroadcasts();
    }
}