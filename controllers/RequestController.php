<?php

require_once __DIR__ . '/../models/BloodRequestModel.php';

class RequestController extends Controller {

    // GET /requests/create - show the "post a request" form.
    public function create(): void {
        // Donors are intentionally excluded here.
        $this->requireRole(['recipient', 'hospital_admin']);
        $this->render('requests/create');
    }

    // POST /requests/save - store the submitted request.
    public function save(): void {
        // Same role guard as create() so the rule cannot be bypassed.
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

    // GET /requests/list - anyone (including guests) may browse all requests.
    public function list(): void {
        $requestModel = new BloodRequestModel();
        $requests     = $requestModel->getAllRequests();

        $this->render('requests/list', ['requests' => $requests]);
    }

    // GET /requests/show - view a single request (public).
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
