<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/DonorMatching.php';
require_once __DIR__ . '/../models/BloodRequest.php';
require_once __DIR__ . '/../models/GeminiService.php';

class MatchingController {
    private $db;
    private $matchingModel;
    private $requestModel;
    private $geminiService;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: /smart_blood_network/views/auth/login.php?msg=login_required');
            exit();
        }

        $database = new Database();
        $this->db = $database->connect();
        $this->matchingModel = new DonorMatching($this->db);
        $this->requestModel = new BloodRequest($this->db);
        $this->geminiService = new GeminiService();
    }

    public function searchDonors() {
        $bloodGroup = $_GET['blood_group'] ?? '';
        $location = $_GET['location'] ?? '';
        $maxDate = $_GET['max_donation_date'] ?? '';

        return $this->matchingModel->filterDonors($bloodGroup, $location, $maxDate);
    }

    public function getAIMatchesForRequest($requestId) {
        $request = $this->requestModel->getById($requestId);
        if (!$request) return ['request' => null, 'matches' => [], 'ai_summary' => 'Request not found'];

        $matches = $this->matchingModel->calculateAIMatches(
            $request['blood_group'],
            $request['latitude'],
            $request['longitude']
        );

        $prompt = "Analyze donor matches for recipient needing " . $request['blood_group'] . " blood group. Available candidates: " . json_encode(array_slice($matches, 0, 5)) . ". Provide brief clinical match recommendation.";
        $systemInstruction = "You are the clinical blood matching AI for Smart Blood Network. Assess ABO/Rh compatibility and donor priority strictly.";

        $aiSummary = $this->geminiService->generateResponse($prompt, $systemInstruction);

        return [
            'request' => $request,
            'matches' => $matches,
            'ai_summary' => $aiSummary
        ];
    }
}