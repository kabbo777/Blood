<?php require_once __DIR__ . '/../navbar.php'; ?>
<div class="d-flex justify-content-center">
    <div class="card shadow-lg border-danger border-3 p-4 text-center" style="max-width: 650px; width: 100%;">
        <div class="card-body">
            <div class="text-danger fs-1 mb-2">🏅</div>
            <h2 class="card-title text-danger fw-bold text-uppercase mb-1">Certificate of Appreciation</h2>
            <p class="text-muted small mb-4">SMART BLOOD NETWORK OFFICIAL RECOGNITION</p>
            <p class="fs-5 mb-1">This digital certificate is proudly presented to</p>
            <h3 class="fw-bold text-dark my-3 border-bottom pb-2 border-danger d-inline-block px-4"><?= htmlspecialchars($cert['donor_name'] ?? 'Valued Donor') ?></h3>
            <p class="card-text text-muted mt-2">
                For generously donating blood (Group: <strong class="text-danger"><?= htmlspecialchars($cert['blood_group'] ?? 'N/A') ?></strong>) on 
                <strong><?= htmlspecialchars($cert['donation_date'] ?? date('Y-m-d')) ?></strong>.
            </p>
            <p class="fst-italic text-danger fw-semibold my-4">"Your selflessness gives someone another chance at life."</p>
            <button onclick="window.print()" class="btn btn-outline-danger mt-2">Print Certificate</button>
        </div>
    </div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
