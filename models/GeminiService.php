<?php
class GeminiService {
    private $apiKey;
    private $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.6-flash:generateContent";

    public function __construct() {
        $this->apiKey = $_ENV['GEMINI_API_KEY'] ?? 'AQ.Ab8RN6KSavrQfq3ejFyMeSuvXhAs1gSf2Ftm8EhiwfIlrd_CMw';
    }

    public function generateResponse($prompt, $systemInstruction = "") {
        if (!function_exists('curl_init')) {
            return "PHP cURL extension is not enabled in your XAMPP installation.";
        }

        $endpoint = $this->apiUrl . "?key=" . trim($this->apiKey);

        $contents = [
            [
                "role" => "user",
                "parts" => [
                    ["text" => $prompt]
                ]
            ]
        ];

        $payload = [
            "contents" => $contents
        ];

        if (!empty($systemInstruction)) {
            $payload["systemInstruction"] = [
                "parts" => [
                    ["text" => $systemInstruction]
                ]
            ];
        }

        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $curlError = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($curlError) {
            return "cURL Error: " . $curlError;
        }

        if (empty($response)) {
            return "Empty response received from Gemini API server.";
        }

        $result = json_decode($response, true);

        if ($httpCode !== 200) {
            if (isset($result['error']['message'])) {
                return "API Error (" . $httpCode . "): " . $result['error']['message'];
            }
            return "HTTP Error (" . $httpCode . "): " . $response;
        }

        if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
            return $result['candidates'][0]['content']['parts'][0]['text'];
        }

        return "Unexpected response format received from Gemini API.";
    }
}