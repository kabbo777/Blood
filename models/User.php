<?php
class User {
    private $conn;
    private $table = 'users';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register($fullName, $email, $phone, $password, $role, $bloodGroup = 'O+') {
        $checkSql = "SELECT id FROM " . $this->table . " WHERE email = :email OR phone = :phone";
        $stmt = $this->conn->prepare($checkSql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return ['status' => false, 'message' => 'Email or Phone already exists'];
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $vCode = rand(100000, 999999);

        $sql = "INSERT INTO " . $this->table . " 
                (full_name, email, phone, password_hash, role, blood_group, verification_code, is_verified, availability_status, reliability_score) 
                VALUES (:full_name, :email, :phone, :password_hash, :role, :blood_group, :v_code, 0, 'Available', 5.00)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':full_name', $fullName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':password_hash', $hashedPassword);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':blood_group', $bloodGroup);
        $stmt->bindParam(':v_code', $vCode);

        if ($stmt->execute()) {
            return [
                'status' => true,
                'user_id' => $this->conn->lastInsertId(),
                'v_code' => $vCode
            ];
        }
        return ['status' => false, 'message' => 'Registration failed'];
    }

    public function login($email, $password) {
        $sql = "SELECT * FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return false;
        }

        $hashInDb = $user['password_hash'] ?? $user['password'] ?? '';

        if (!empty($hashInDb) && password_verify($password, $hashInDb)) {
            return $user;
        }
        return false;
    }

    public function verifyAccount($userId, $code) {
        $sql = "SELECT id FROM " . $this->table . " WHERE id = :id AND verification_code = :code";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $userId);
        $stmt->bindParam(':code', $code);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $updateSql = "UPDATE " . $this->table . " SET is_verified = 1, verification_code = NULL WHERE id = :id";
            $updateStmt = $this->conn->prepare($updateSql);
            $updateStmt->bindParam(':id', $userId);
            return $updateStmt->execute();
        }
        return false;
    }

    public function getById($userId) {
        $sql = "SELECT id, full_name, email, phone, role, 
                       COALESCE(blood_group, 'O+') as blood_group, 
                       COALESCE(reliability_score, 5.00) as reliability_score, 
                       COALESCE(anonymous_mode, 0) as anonymous_mode, 
                       COALESCE(location, 'Not Specified') as location, 
                       COALESCE(latitude, 23.8103) as latitude, 
                       COALESCE(longitude, 90.4125) as longitude,
                       availability_status, is_verified 
                FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function updateLocation($userId, $location, $lat, $lng) {
        $sql = "UPDATE " . $this->table . " SET location = :location, latitude = :lat, longitude = :lng WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':lat', $lat);
        $stmt->bindParam(':lng', $lng);
        $stmt->bindParam(':id', $userId);
        return $stmt->execute();
    }

    public function updateBloodGroup($userId, $bloodGroup) {
        $sql = "UPDATE " . $this->table . " SET blood_group = :blood_group WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':blood_group', $bloodGroup);
        $stmt->bindParam(':id', $userId);
        return $stmt->execute();
    }

    public function updateAvailability($userId, $status) {
        $sql = "UPDATE " . $this->table . " SET availability_status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $userId);
        return $stmt->execute();
    }

    public function toggleAnonymousMode($userId, $isAnonymous) {
        $val = $isAnonymous ? 1 : 0;
        $sql = "UPDATE " . $this->table . " SET anonymous_mode = :val WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':val', $val, PDO::PARAM_INT);
        $stmt->bindParam(':id', $userId);
        return $stmt->execute();
    }
}