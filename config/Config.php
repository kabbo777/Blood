<?php
define('BASE_URL', '/smart_blood_network/views');
define('SITE_NAME', 'Smart Blood Network');
if (!defined('GEMINI_API_KEY')) {
    define('GEMINI_API_KEY', $_ENV['GEMINI_API_KEY'] ?? 'AQ.Ab8RN6KSavrQfq3ejFyMeSuvXhAs1gSf2Ftm8EhiwfIlrd_CMw');
}
if (!defined('GOOGLE_MAPS_API_KEY')) {
    define('GOOGLE_MAPS_API_KEY', $_ENV['GOOGLE_MAPS_API_KEY'] ?? 'AIzaSyAPrT7Xra9IdrxJKqyoOJ5IcNm0urEOE28');
}