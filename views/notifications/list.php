<?php require_once __DIR__ . '/../navbar.php'; ?>
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h4 class="text-danger fw-bold mb-0">Notification Center</h4>
    </div>
    <div class="card-body p-0">
        <?php if (empty($notifications)): ?>
            <p class="text-muted p-4 text-center mb-0">No notifications available.</p>
        <?php else: ?>
            <div class="list-group list-group-flush">
                <?php foreach ($notifications as $notif): ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center py-3 <?= $notif['is_read'] ? 'bg-white' : 'bg-light border-start border-danger border-4' ?>">
                        <div>
                            <p class="mb-1 <?= $notif['is_read'] ? 'text-secondary' : 'fw-bold text-dark' ?>"><?= htmlspecialchars($notif['message']) ?></p>
                            <small class="text-muted"><?= htmlspecialchars($notif['created_at']) ?></small>
                        </div>
                        <?php if (!$notif['is_read']): ?>
                            <form action="/smart_blood_network/notifications/read" method="POST" class="ms-3">
                                <input type="hidden" name="notification_id" value="<?= $notif['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger">Mark Read</button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>