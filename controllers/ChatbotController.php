<?php

require_once __DIR__ . '/../config/Config.php';

class ChatbotController extends Controller {

    public function index(): void {
        $this->render('chatbot/index');
    }

    // POST /chatbot/chat
    public function chat(): void {
        error_reporting(0);
        header('Content-Type: application/json');

        $apiKey = Config::get('gemini_api_key');
        $url    = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.6-flash:generateContent?key=" . $apiKey;

        $input       = json_decode(file_get_contents('php://input'), true);
        $userMessage = trim($input['message'] ?? '');

        if (empty($userMessage)) {
            echo json_encode(['reply' => 'Please enter a message.']);
            return;
        }

        $data = [
            "contents" => [[
                "parts" => [[
                    "text" => "You are a helpful blood donation medical assistant for Smart Blood Network Bangladesh. "
                            . "Reply concisely and helpfully to: " . $userMessage
                ]]
            ]]
        ];

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($data),
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_TIMEOUT        => 20,
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo json_encode(['reply' => 'Network error: ' . curl_error($ch)]);
            curl_close($ch);
            return;
        }
        curl_close($ch);

        $responseData = json_decode($response, true);

        if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
            $botReply = $responseData['candidates'][0]['content']['parts'][0]['text'];
        } else {
            $botReply = $responseData['error']['message'] ?? "Sorry, I couldn't process that right now.";
        }

        echo json_encode(['reply' => $botReply]);
    }
}
