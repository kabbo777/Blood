<?php
require_once __DIR__ . '/../config/Database.php';

class Notification {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getNotificationsForUser($userId) {
        $stmt = $this->db->prepare("SELECT * FROM notifications WHERE user_id = :user_id ORDER BY created_at DESC");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function markAsRead($notificationId, $userId) {
        $stmt = $this->db->prepare("UPDATE notifications SET is_read = 1 WHERE id = :id AND user_id = :user_id");
        return $stmt->execute(['id' => $notificationId, 'user_id' => $userId]);
    }
}