<?php
require_once __DIR__ . '/../models/DonorMatching.php';

class DonorMatchController extends Controller {
    public function search() {
        $bloodGroup = filter_input(INPUT_GET, 'blood_group', FILTER_SANITIZE_SPECIAL_CHARS) ?: 'A+';
        $lat        = filter_input(INPUT_GET, 'latitude', FILTER_VALIDATE_FLOAT) ?: 23.8103;
        $lng        = filter_input(INPUT_GET, 'longitude', FILTER_VALIDATE_FLOAT) ?: 90.4125;

        $matchingModel = new DonorMatching();
        $matches       = $matchingModel->findMatchesWithScoring($bloodGroup, $lat, $lng);

        $this->render('donor/search', [
            'matches'          => $matches,
            'targetBloodGroup' => $bloodGroup
        ]);
    }
}