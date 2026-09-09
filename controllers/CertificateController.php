<?php
require_once __DIR__ . '/../models/CertificateModel.php';

class CertificateController extends Controller {
    public function view() {
        $this->requireRole(['donor']);

        $donationId = filter_input(INPUT_GET, 'donation_id', FILTER_VALIDATE_INT);
        $certModel  = new CertificateModel();
        $certData   = $certModel->getCertificateData($_SESSION['user_id'], $donationId);

        $this->render('certificate/view', ['cert' => $certData]);
    }
}
