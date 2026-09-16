<?php

require_once __DIR__ . '/../navbar.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm border-0 p-4 text-center">
            <h3 class="text-danger fw-bold mb-1">Emergency Request #<?= htmlspecialchars((string)$req['id']) ?></h3>
            <p class="text-muted small mb-4">Status:
                <span class="badge <?= $req['status'] === 'Active' ? 'bg-danger' : 'bg-secondary' ?>">
                    <?= htmlspecialchars($req['status']) ?>
                </span>
            </p>

            <div class="mb-3">
                <span class="badge bg-danger fs-5 mb-2"><?= htmlspecialchars($req['blood_group']) ?> Blood Needed</span>
                <p class="fs-5 mb-1">
                    <strong>Type:</strong> <?= htmlspecialchars($req['requirement_type'] ?? 'Blood') ?>
                    &nbsp;|&nbsp;
                    <strong>Units:</strong> <?= htmlspecialchars((string)($req['units_required'] ?? '?')) ?>
                </p>
                <p class="mb-1"><strong>Hospital:</strong> <?= htmlspecialchars($req['hospital_name']) ?></p>

                <p class="text-muted"><strong>Location:</strong> <?= htmlspecialchars($req['location'] ?? '') ?></p>

                <p class="mb-1">
                    <strong>Urgency:</strong>
                    <span class="badge bg-<?= $req['urgency_level'] === 'Emergency_SOS' ? 'danger' : ($req['urgency_level'] === 'High' ? 'warning text-dark' : 'secondary') ?>">
                        <?= htmlspecialchars($req['urgency_level']) ?>
                    </span>
                </p>

                <?php if (!($req['is_anonymous'] ?? false)): ?>
                    <p class="mb-1"><strong>Contact:</strong> <?= htmlspecialchars($req['contact_number'] ?? '') ?></p>
                <?php endif; ?>

                <?php if (!empty($req['medical_details'])): ?>
                    <p class="text-muted small mt-2"><strong>Notes:</strong> <?= htmlspecialchars($req['medical_details']) ?></p>
                <?php endif; ?>
            </div>

            <hr class="my-3">

            <h5 class="fw-bold mb-3">📲 One-Tap Social Sharing</h5>
            <?php
                $pageUrl   = rawurlencode("http://" . $_SERVER['HTTP_HOST'] . "/smart_blood_network/requests/show?id=" . $req['id']);
                $shareText = rawurlencode("🚨 EMERGENCY BLOOD NEEDED: Group " . $req['blood_group'] . " at " . $req['hospital_name'] . ". Please help!");
            ?>

            <div class="d-grid gap-2">
                <a href="https://api.whatsapp.com/send?text=<?= $shareText ?>%20<?= $pageUrl ?>"
                   target="_blank" class="btn btn-success fw-bold py-2">
                   💬 Share on WhatsApp
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $pageUrl ?>&quote=<?= $shareText ?>"
                   target="_blank" class="btn btn-primary fw-bold py-2">
                   📘 Share on Facebook
                </a>
                <?php if (!($req['is_anonymous'] ?? false) && !empty($req['contact_number'])): ?>
                <a href="tel:<?= htmlspecialchars($req['contact_number']) ?>"
                   class="btn btn-outline-danger fw-bold py-2">
                   📞 Call Contact
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
