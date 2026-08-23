<?php
error_reporting(0);
ini_set('display_errors', 0);

if (ob_get_level()) {
    ob_end_clean();
}
ob_start();

header('Content-Type: application/json; charset=utf-8');

register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== NULL && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        if (ob_get_level()) {
            ob_end_clean();
        }
        echo json_encode([
            'status' => 'error',
            'message' => 'PHP Fatal Error: ' . $error['message'] . ' in ' . $error['file'] . ' on line ' . $error['line']
        ]);
    }
});

require_once __DIR__ . '/../models/GeminiService.php';

class AIController {
    private $geminiService;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->geminiService = new GeminiService();
    }

    public function handleChatRequest() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->sendJson(['status' => 'error', 'message' => 'Invalid request method.']);
            }

            $rawInput = file_get_contents('php://input');
            $input = json_decode($rawInput, true);
            $userPrompt = trim($input['message'] ?? '');

            if (empty($userPrompt)) {
                $this->sendJson(['status' => 'error', 'message' => 'Message cannot be empty.']);
            }

            $systemInstruction = "You are the official AI Assistant for the Smart Blood Network platform. Provide accurate, helpful, and clear responses regarding blood donation eligibility, blood group compatibility, donor guidelines, and emergency donor matching. Always advise users to consult healthcare professionals for critical medical emergencies.";

            $reply = $this->geminiService->generateResponse($userPrompt, $systemInstruction);

            $this->sendJson(['status' => 'success', 'reply' => $reply]);
        } catch (Throwable $e) {
            $this->sendJson(['status' => 'error', 'message' => 'Controller Exception: ' . $e->getMessage()]);
        }
    }

    private function sendJson($data) {
        if (ob_get_level()) {
            ob_end_clean();
        }
        echo json_encode($data);
        exit;
    }
}

if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    $controller = new AIController();
    $controller->handleChatRequest();
}