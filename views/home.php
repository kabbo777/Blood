<?php
// views/home.php
//
// ISSUE #1 FIX: the "Post Requirement" card is only shown to recipients &
//               hospital admins (donors cannot post requests).
// ISSUE #2 FIX: the "Hospital Inventory" card is shown to every logged-in
//               user, but the wording/button switches between "Manage Stock"
//               (admins) and "View Inventory" (everyone else).
// Earlier fix kept: use $_SESSION['role'] (not the never-set 'user_role').
require_once __DIR__ . '/navbar.php';

$role          = $_SESSION['role'] ?? '';
$canRequest    = in_array($role, ['recipient', 'hospital_admin'], true);   // Issue #1
$isInvManager  = in_array($role, ['hospital_admin', 'admin'], true);       // Issue #2
?>

<div class="p-4 mb-4 bg-white rounded-3 shadow-sm border">
    <h1 class="display-6 fw-bold text-danger">Welcome to Smart Blood Network 🩸</h1>
    <p class="col-md-9 fs-5 text-muted">
        Manage blood requests, locate nearby donors, track health eligibility, and save lives in real-time.
    </p>

    <?php if (isset($_SESSION['user_id'])): ?>
        <p class="mb-0">
            Logged in as <strong><?= htmlspecialchars($_SESSION['name'] ?? '') ?></strong>
            <span class="badge bg-danger ms-1"><?= htmlspecialchars($role) ?></span>
        </p>
    <?php endif; ?>

    <?php if ($role === 'donor'): ?>
        <form action="/smart_blood_network/donor/status/toggle" method="POST" class="mt-3">
            <div class="input-group" style="max-width:320px;">
                <span class="input-group-text bg-light fw-bold">My Status:</span>
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="Available"   <?= (($_SESSION['user_status'] ?? '') === 'Available')   ? 'selected' : '' ?>>🟢 Available</option>
                    <option value="Resting"     <?= (($_SESSION['user_status'] ?? '') === 'Resting')     ? 'selected' : '' ?>>🟡 Resting (Cooldown)</option>
                    <option value="Unavailable" <?= (($_SESSION['user_status'] ?? '') === 'Unavailable') ? 'selected' : '' ?>>🔴 Unavailable</option>
                </select>
            </div>
        </form>
    <?php endif; ?>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title text-danger">🔍 Search Donors</h5>
                <p class="card-text text-muted small">AI-ranked compatible donors near you.</p>
                <a href="/smart_blood_network/donor/search" class="btn btn-outline-danger btn-sm">Find Donors</a>
            </div>
        </div>
    </div>

    <?php // ISSUE #1 FIX: only recipients & hospital admins may post a request. ?>
    <?php if ($canRequest): ?>
    <div class="col-md-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title text-danger">📋 Post Requirement</h5>
                <p class="card-text text-muted small">Post an emergency blood or platelet request.</p>
                <a href="/smart_blood_network/requests/create" class="btn btn-danger btn-sm">Create Request</a>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="col-md-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title text-danger">🗺️ Map Explorer</h5>
                <p class="card-text text-muted small">See nearby donors, hospitals &amp; blood banks.</p>
                <a href="/smart_blood_network/map" class="btn btn-outline-danger btn-sm">View Map</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title text-danger">🏆 Badges &amp; Milestones</h5>
                <p class="card-text text-muted small">Earn badges for your donation achievements.</p>
                <a href="/smart_blood_network/donor/badges" class="btn btn-outline-secondary btn-sm">My Badges</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title text-danger">🩺 Eligibility Screener</h5>
                <p class="card-text text-muted small">Check if you are eligible to donate today.</p>
                <a href="/smart_blood_network/screener" class="btn btn-outline-secondary btn-sm">Start Health Check</a>
            </div>
        </div>
    </div>

    <?php // ISSUE #2 FIX: visible to all logged-in users; label depends on role. ?>
    <?php if (isset($_SESSION['user_id'])): ?>
    <div class="col-md-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title text-danger">🏥 Hospital Inventory</h5>
                <p class="card-text text-muted small">
                    <?= $isInvManager
                        ? 'Manage blood bag stock and expiry tracking.'
                        : 'View available blood bag stock and expiry dates.' ?>
                </p>
                <a href="/smart_blood_network/admin/inventory" class="btn btn-outline-secondary btn-sm">
                    <?= $isInvManager ? 'Manage Stock' : 'View Inventory' ?>
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="col-md-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title text-danger">📋 All Requests</h5>
                <p class="card-text text-muted small">Browse all active blood requests.</p>
                <a href="/smart_blood_network/requests/list" class="btn btn-outline-secondary btn-sm">View Requests</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title text-danger">📊 District Leaderboard</h5>
                <p class="card-text text-muted small">Top donors in your district.</p>
                <a href="/smart_blood_network/leaderboard/district" class="btn btn-outline-secondary btn-sm">View Leaderboard</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title text-danger">💳 Make a Payment</h5>
                <p class="card-text text-muted small">bKash, Nagad, or card for processing fees.</p>
                <a href="/smart_blood_network/payment/checkout" class="btn btn-outline-secondary btn-sm">Pay Now</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
