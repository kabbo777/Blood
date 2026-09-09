<?php
class Config {
    public static function get($key, $default = null) {
        $config = [
            'db_host'             => getenv('DB_HOST') ?: '127.0.0.1',
            'db_name'             => getenv('DB_NAME') ?: 'smart_blood_db',
            'db_user'             => getenv('DB_USER') ?: 'root',
            'db_pass'             => getenv('DB_PASS') ?: '',
            'gemini_api_key'      => getenv('GEMINI_API_KEY') ?: 'AQ.Ab8RN6KSavrQfq3ejFyMeSuvXhAs1gSf2Ftm8EhiwfIlrd_CMw',
            'google_maps_api_key' => getenv('GOOGLE_MAPS_API_KEY') ?: 'AIzaSyAPrT7Xra9IdrxJKqyoOJ5IcNm0urEOE28'
        ];

        return $config[$key] ?? $default;
    }
}
