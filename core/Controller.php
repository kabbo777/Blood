<?php


abstract class Controller {
    protected function render(string $view, array $data = []): void {
        extract($data);
        $file = __DIR__ . "/../views/{$view}.php";
        if (file_exists($file)) {
            require $file;
        } else {
            die("View file '{$view}' not found.");
        }
    }

    protected function requireRole(array $allowedRoles = []): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header("Location: /smart_blood_network/login");
            exit();
        }

        if (!empty($allowedRoles) && !in_array($_SESSION['role'] ?? '', $allowedRoles)) {
            http_response_code(403);
            die("Unauthorized access: insufficient privileges.");
        }
    }
}
