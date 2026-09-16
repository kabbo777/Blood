<?php

require_once __DIR__ . '/../models/BloodRequestModel.php';

class RequestController extends Controller {

    public function create(): void {
        $this->requireRole(['recipient', 'hospital_admin']);
        $this->render('requests/create');
    }

    public function save(): void {
        $this->requireRole(['recipient', 'hospital_admin']);

        $patientName      = filter_input(INPUT_POST, 'patient_name',      FILTER_SANITIZE_SPECIAL_CHARS);
        $bloodGroup       = filter_input(INPUT_POST, 'blood_group',       FILTER_SANITIZE_SPECIAL_CHARS);
        $requirementType  = filter_input(INPUT_POST, 'requirement_type',  FILTER_SANITIZE_SPECIAL_CHARS) ?: 'Blood';
        $units            = filter_input(INPUT_POST, 'units_required',    FILTER_VALIDATE_INT) ?: 1;
        $hospitalName     = filter_input(INPUT_POST, 'hospital_name',     FILTER_SANITIZE_SPECIAL_CHARS);
        $location         = filter_input(INPUT_POST, 'location',          FILTER_SANITIZE_SPECIAL_CHARS);
        $urgency          = filter_input(INPUT_POST, 'urgency_level',     FILTER_SANITIZE_SPECIAL_CHARS);
        $contactNumber    = filter_input(INPUT_POST, 'contact_number',    FILTER_SANITIZE_SPECIAL_CHARS);
        $medicalDetails   = filter_input(INPUT_POST, 'medical_details',   FILTER_SANITIZE_SPECIAL_CHARS) ?: '';
        $isAnonymous      = isset($_POST['is_anonymous']);

        $requestModel = new BloodRequestModel();
        $requestId    = $requestModel->createRequest(
            (int) $_SESSION['user_id'],
            $patientName,
            $bloodGroup,
            $requirementType,
            $units,
            $hospitalName,
            $location,
            $urgency,
            $isAnonymous,
            $contactNumber,
            $medicalDetails
        );

        header("Location: /smart_blood_network/requests/show?id=" . $requestId);
        exit();
    }

    public function list(): void {
        $requestModel = new BloodRequestModel();
        $requests     = $requestModel->getAllRequests();

        $this->render('requests/list', ['requests' => $requests]);
    }

    public function show(): void {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            http_response_code(404);
            die("Request not found.");
        }

        $requestModel = new BloodRequestModel();
        $req          = $requestModel->getById($id);

        if (!$req) {
            http_response_code(404);
            die("Blood request #" . htmlspecialchars((string)$id) . " not found.");
        }

        $this->render('requests/show', ['req' => $req]);
    }
}
