<?php
class Config {
    public static function get($key, $default = null) {
        $config = [
            'db_host' => getenv('DB_HOST') ?: '127.0.0.1',
            'db_name' => getenv('DB_NAME') ?: 'blood_network',
            'db_user' => getenv('DB_USER') ?: 'root',
            'db_pass' => getenv('DB_PASS') ?: '',
            'gemini_api_key' => getenv('GEMINI_API_KEY') ?: ''
        ];

        return $config[$key] ?? $default;
    }
}
