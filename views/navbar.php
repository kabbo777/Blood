<!DOCTYPE html>
<!-- views/navbar.php -->
<!-- Shared top navigation. Included at the top of every view. -->
<!--
  ISSUE #1 FIX: "Request Blood" is only shown to recipients & hospital admins
                (and only when logged in). Donors and guests never see it.
  ISSUE #2 FIX: "Inventory" is shown to EVERY logged-in user, because everyone
                is allowed to VIEW inventory. (Only admins can manage stock,
                which is gated inside the inventory page itself.)
  Earlier fix kept: hamburger toggle button (mobile menu).
-->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Blood Network</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .navbar-brand { letter-spacing: .5px; }
        .nav-link:hover { opacity: .85; }
        .badge-count { font-size: .65rem; }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-danger shadow-sm mb-4">
  <div class="container">
    <a class="navbar-brand fw-bold" href="/smart_blood_network/home">
      🩸 Smart Blood Network
    </a>

    <button class="navbar-toggler" type="button"
            data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center gap-1">

        <!-- Public links (visible to everyone) -->
        <li class="nav-item">
          <a class="nav-link" href="/smart_blood_network/home"><i class="bi bi-house-fill me-1"></i>Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/smart_blood_network/donor/search"><i class="bi bi-search-heart me-1"></i>Find Donors</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/smart_blood_network/requests/list"><i class="bi bi-list-ul me-1"></i>All Requests</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/smart_blood_network/map"><i class="bi bi-map-fill me-1"></i>Map</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/smart_blood_network/chatbot"><i class="bi bi-robot me-1"></i>AI Chat</a>
        </li>

        <?php if (isset($_SESSION['user_id'])): ?>

          <?php
            // ISSUE #1 FIX: only recipients & hospital admins may request blood.
            $canRequestBlood = in_array($_SESSION['role'] ?? '', ['recipient', 'hospital_admin'], true);
          ?>
          <?php if ($canRequestBlood): ?>
            <li class="nav-item">
              <a class="nav-link" href="/smart_blood_network/requests/create"><i class="bi bi-droplet-fill me-1"></i>Request Blood</a>
            </li>
          <?php endif; ?>

          <li class="nav-item">
            <a class="nav-link" href="/smart_blood_network/screener"><i class="bi bi-clipboard2-pulse me-1"></i>Health Check</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/smart_blood_network/notifications"><i class="bi bi-bell-fill me-1"></i>Alerts</a>
          </li>

          <?php // ISSUE #2 FIX: every logged-in user can VIEW the inventory. ?>
          <li class="nav-item">
            <a class="nav-link" href="/smart_blood_network/admin/inventory"><i class="bi bi-boxes me-1"></i>Inventory</a>
          </li>

          <?php if (($_SESSION['role'] ?? '') === 'donor'): ?>
            <li class="nav-item">
              <a class="nav-link" href="/smart_blood_network/donor/history"><i class="bi bi-clock-history me-1"></i>History</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/smart_blood_network/donor/badges"><i class="bi bi-trophy-fill me-1"></i>Badges</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/smart_blood_network/leaderboard/district"><i class="bi bi-bar-chart-fill me-1"></i>Leaderboard</a>
            </li>
          <?php endif; ?>

          <li class="nav-item ms-2">
            <a class="btn btn-sm btn-outline-light" href="/smart_blood_network/logout">
              <i class="bi bi-box-arrow-right me-1"></i>Logout (<?= htmlspecialchars($_SESSION['name'] ?? '') ?>)
            </a>
          </li>
        <?php else: ?>
          <li class="nav-item ms-2">
            <a class="btn btn-sm btn-outline-light" href="/smart_blood_network/login">Login</a>
          </li>
          <li class="nav-item ms-1">
            <a class="btn btn-sm btn-light text-danger fw-bold" href="/smart_blood_network/register">Register</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<div class="container pb-5">
