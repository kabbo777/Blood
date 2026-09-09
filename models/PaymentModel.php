<?php

require_once __DIR__ . '/../config/Database.php';

class PaymentModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function recordTransaction(int $userId, float $amount, string $method, string $transactionId): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO `payments` (`user_id`,`amount`,`payment_method`,`transaction_id`,`status`)
             VALUES (:uid,:amount,:method,:txn_id,'Completed')"
        );
        return $stmt->execute([
            'uid'    => $userId,
            'amount' => $amount,
            'method' => $method,
            'txn_id' => $transactionId,
        ]);
    }


    public function getPaymentsByUser(int $userId): array {
        $stmt = $this->db->prepare(
            "SELECT * FROM `payments` WHERE `user_id` = ? ORDER BY `created_at` DESC"
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
}
