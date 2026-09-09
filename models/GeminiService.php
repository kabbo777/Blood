<?php
require_once __DIR__ . '/../config/Config.php';

class GeminiService {
    private $apiKey;
    private $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent";

    public function __construct() {
        $this->apiKey = Config::get('gemini_api_key');
    }

    public function generateResponse($prompt, $systemInstruction = "") {
        if (!function_exists('curl_init')) {
            return "PHP cURL extension is not enabled.";
        }

        $endpoint = $this->apiUrl . "?key=" . trim($this->apiKey);

        $payload = [
            "contents" => [
                ["role" => "user", "parts" => [["text" => $prompt]]]
            ]
        ];

        if (!empty($systemInstruction)) {
            $payload["systemInstruction"] = ["parts" => [["text" => $systemInstruction]]];
        }

        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $result = json_decode($response, true);
        if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
            return $result['candidates'][0]['content']['parts'][0]['text'];
        }

        return "Error reaching API.";
    }
}
