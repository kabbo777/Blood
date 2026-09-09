<?php

require_once __DIR__ . '/../config/Database.php';

class UserModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function register(
        string  $name,
        string  $email,
        string  $password,
        string  $phone,
        string  $role,
        string  $bloodGroup,
        string  $district,
        ?string $latitude,
        ?string $longitude,
        string  $fullAddress,
        int     $isVerified = 0
    ): bool {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare(
            "INSERT INTO `users`
                (`name`,`email`,`password`,`phone`,`role`,`blood_group`,
                 `district`,`latitude`,`longitude`,`full_address`,`is_verified`,`health_check_passed`)
             VALUES (?,?,?,?,?,?,?,?,?,?,?,0)"
        );
        return $stmt->execute([
            $name, $email, $hash, $phone, $role,
            $bloodGroup, $district, $latitude, $longitude, $fullAddress, $isVerified,
        ]);
    }

    public function login(string $email, string $password): array|false {
        $stmt = $this->db->prepare("SELECT * FROM `users` WHERE `email` = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function findById(int $userId): array|false {
        $stmt = $this->db->prepare("SELECT * FROM `users` WHERE `id` = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }

    public function updateStatus(int $userId, string $status): bool {
        $stmt = $this->db->prepare("UPDATE `users` SET `status` = ? WHERE `id` = ?");
        return $stmt->execute([$status, $userId]);
    }

    public function updateHealthStatus(int $userId, int $passed): bool {
        $stmt = $this->db->prepare("UPDATE `users` SET `health_check_passed` = ? WHERE `id` = ?");
        return $stmt->execute([$passed, $userId]);
    }

    public function getMapLocations(): array {
        $stmt = $this->db->prepare(
            "SELECT `id`, `name`, `blood_group`, `district`, `status`,
                    `latitude`, `longitude`, `full_address`
             FROM `users`
             WHERE `role` = 'donor'
               AND `latitude`  IS NOT NULL
               AND `longitude` IS NOT NULL"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
