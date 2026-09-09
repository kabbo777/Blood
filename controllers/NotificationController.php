<?php
require_once __DIR__ . '/../models/Notification.php';

class NotificationController extends Controller {
    public function list() {
        $this->requireRole(['donor', 'recipient', 'hospital_admin']);

        $notifModel    = new Notification();
        $notifications = $notifModel->getNotificationsForUser($_SESSION['user_id']);

        $this->render('notifications/list', ['notifications' => $notifications]);
    }

    public function markRead() {
        $this->requireRole(['donor', 'recipient', 'hospital_admin']);

        $notifId = filter_input(INPUT_POST, 'notification_id', FILTER_VALIDATE_INT);
        if ($notifId) {
            $notifModel = new Notification();
            $notifModel->markAsRead($notifId, $_SESSION['user_id']);
        }

        header("Location: /smart_blood_network/notifications");
        exit();
    }
}