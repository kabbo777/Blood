<?php
require_once __DIR__ . '/../../controllers/DonorProfileController.php';
$profileCtrl = new DonorProfileController();
$locationMsg = $profileCtrl->updateLocation();
$availMsg = $profileCtrl->updateAvailability();
$privacyMsg = $profileCtrl->toggleAnonymousMode();
$data = $profileCtrl->getProfileData();
$profile = $data['profile'] ?? [];
$cooldown = $data['cooldown'] ?? [];
?>
<!DOCTYPE html>
<html>
<head><title>Donor Dashboard - Smart Blood Network</title></head>
<body>
    <?php include_once __DIR__ . '/../header.php'; ?>

    <h2>Donor Control Panel</h2>

    <?php if (isset($locationMsg['success'])): ?><p style="color:green; font-weight:bold;"><?= $locationMsg['success'] ?></p><?php endif; ?>
    <?php if (isset($availMsg['success'])): ?><p style="color:green; font-weight:bold;"><?= $availMsg['success'] ?></p><?php endif; ?>
    <?php if (isset($privacyMsg['success'])): ?><p style="color:green; font-weight:bold;"><?= $privacyMsg['success'] ?></p><?php endif; ?>

    <div style="border: 1px solid #ccc; padding: 15px; margin-bottom: 20px;">
        <h3>Profile Overview</h3>
        <p><strong>Name:</strong> <?= htmlspecialchars($profile['full_name'] ?? 'N/A') ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($profile['email'] ?? 'N/A') ?></p>
        <p><strong>Blood Group:</strong> <strong><?= htmlspecialchars($profile['blood_group'] ?? 'Not Specified') ?></strong></p>
        <p><strong>Current Area:</strong> <?= htmlspecialchars($profile['location'] ?? 'Not Specified') ?> (Lat: <?= htmlspecialchars($profile['latitude'] ?? '23.8103') ?>, Lng: <?= htmlspecialchars($profile['longitude'] ?? '90.4125') ?>)</p>
        <p><strong>Role:</strong> <?= strtoupper(htmlspecialchars($profile['role'] ?? 'N/A')) ?></p>
        <p><strong>Reliability Score:</strong> <?= htmlspecialchars($profile['reliability_score'] ?? '5.00') ?> / 5.0</p>
    </div>

    <!-- Live GPS Location Tracker Panel -->
    <div style="border: 1px solid #007bff; background-color: #f4f8ff; padding: 15px; margin-bottom: 20px;">
        <h3>Update Live Map Location</h3>
        <p>Keep your location updated so recipients can view you on the live interactive map during blood emergencies.</p>
        
        <form method="POST" action="">
            <input type="hidden" name="update_location" value="1">
            
            <label>Location / District Name:</label><br>
            <input type="text" id="loc_name" name="location" value="<?= htmlspecialchars($profile['location'] ?? 'Dhaka') ?>" required><br><br>

            <label>Latitude:</label><br>
            <input type="text" id="loc_lat" name="latitude" value="<?= htmlspecialchars($profile['latitude'] ?? '23.8103') ?>" readonly style="background:#e9ecef;"><br><br>

            <label>Longitude:</label><br>
            <input type="text" id="loc_lng" name="longitude" value="<?= htmlspecialchars($profile['longitude'] ?? '90.4125') ?>" readonly style="background:#e9ecef;"><br><br>

            <button type="button" onclick="detectGPS()" style="background:#17a2b8; color:white; padding:8px 12px; border:none; border-radius:3px; margin-right:10px;">
                Detect My GPS Location
            </button>
            <button type="submit" style="background:#28a745; color:white; padding:8px 12px; border:none; border-radius:3px;">
                Save Live Location
            </button>
            <span id="gps_status" style="margin-left:10px; font-weight:bold;"></span>
        </form>
    </div>

    <!-- Feature 4: Automated Cooldown Tracker -->
    <div style="border: 1px solid #17a2b8; background-color: #e9f7f9; padding: 15px; margin-bottom: 20px;">
        <h3>Donation Cooldown Tracker</h3>
        <?php if (!empty($cooldown['last_donation_date'])): ?>
            <p><strong>Last Donation Date:</strong> <?= htmlspecialchars($cooldown['last_donation_date']) ?></p>
            <p><strong>Days Since Last Donation:</strong> <?= $cooldown['days_passed'] ?? 0 ?> days</p>
            <?php if (!empty($cooldown['is_in_cooldown'])): ?>
                <p style="color: red; font-weight: bold;">
                    Status: In Cooldown Period (<?= $cooldown['days_remaining'] ?? 0 ?> days remaining until next eligible donation)
                </p>
            <?php else: ?>
                <p style="color: green; font-weight: bold;">Status: Eligible to Donate Now</p>
            <?php endif; ?>
        <?php else: ?>
            <p>No donation records found. You are currently eligible to donate.</p>
        <?php endif; ?>
    </div>

    <div style="border: 1px solid #ccc; padding: 15px; margin-bottom: 20px;">
        <h3>Update Availability Status</h3>
        <form method="POST" action="">
            <input type="hidden" name="update_availability" value="1">
            <select name="availability_status" <?= !empty($cooldown['is_in_cooldown']) ? 'disabled' : '' ?>>
                <option value="Available" <?= ($profile['availability_status'] ?? '') === 'Available' ? 'selected' : '' ?>>Available</option>
                <option value="Unavailable" <?= ($profile['availability_status'] ?? '') === 'Unavailable' ? 'selected' : '' ?>>Unavailable</option>
                <option value="Resting" <?= ($profile['availability_status'] ?? '') === 'Resting' ? 'selected' : '' ?>>Resting</option>
            </select>
            <?php if (!empty($cooldown['is_in_cooldown'])): ?>
                <span style="color: red;">(Locked to Resting due to 90-day cooldown)</span>
            <?php else: ?>
                <button type="submit">Update Status</button>
            <?php endif; ?>
        </form>
    </div>
    <div style="border: 1px solid #ccc; padding: 15px;">
        <h3>Privacy Settings</h3>
        <form method="POST" action="">
            <input type="hidden" name="update_privacy" value="1">
            <label>
                <input type="checkbox" name="anonymous_mode" value="1" <?= !empty($profile['anonymous_mode']) ? 'checked' : '' ?>>
                Enable Anonymous Mode (Hide phone number and full name from public search & map)
            </label><br><br>
            <button type="submit">Save Privacy Settings</button>
        </form>
    </div>
    <script>
        function detectGPS() {
            var status = document.getElementById('gps_status');
            if (!navigator.geolocation) {
                status.innerText = 'Geolocation is not supported by your browser.';
                status.style.color = 'red';
                return;
            }
            status.innerText = 'Locating...';
            status.style.color = 'blue';
            navigator.geolocation.getCurrentPosition(function(position) {
                document.getElementById('loc_lat').value = position.coords.latitude.toFixed(6);
                document.getElementById('loc_lng').value = position.coords.longitude.toFixed(6);
                status.innerText = 'GPS location acquired! Click Save Live Location.';
                status.style.color = 'green';
            }, function(error) {
                status.innerText = 'Unable to fetch location: ' + error.message;
                status.style.color = 'red';
            });
        }
    </script>
</body>
</html>