<?php
require_once __DIR__ . '/../../controllers/EmergencyController.php';
$emergencyCtrl = new EmergencyController();
$res = $emergencyCtrl->handleCreateRequest();
?>
<!DOCTYPE html>
<html>
<head><title>Post Blood Request - Smart Blood Network</title></head>
<body>
    <?php include_once __DIR__ . '/../header.php'; ?>

    <h2>Post Blood / Platelet Requirement</h2>

    <?php if (isset($res['error'])): ?>
        <p style="color:red;"><?= htmlspecialchars($res['error']) ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Patient Name:</label><br>
        <input type="text" name="patient_name" required><br><br>

        <label>Required Blood Group:</label><br>
        <select name="blood_group" required>
            <option value="A+">A+</option><option value="A-">A-</option>
            <option value="B+">B+</option><option value="B-">B-</option>
            <option value="O+">O+</option><option value="O-">O-</option>
            <option value="AB+">AB+</option><option value="AB-">AB-</option>
        </select><br><br>

        <label>Requirement Type:</label><br>
        <select name="requirement_type" required>
            <option value="Blood">Whole Blood</option>
            <option value="Platelets">Platelets</option>
            <option value="Plasma">Plasma</option>
        </select><br><br>

        <label>Urgency Level:</label><br>
        <select name="urgency_level" required>
            <option value="Emergency_SOS" style="color:red; font-weight:bold;">EMERGENCY SOS BROADCAST</option>
            <option value="High">High Urgency (Within 6 Hours)</option>
            <option value="Medium">Medium (Within 24 Hours)</option>
            <option value="Low">Low (Scheduled)</option>
        </select><br><br>

        <label>Units Required:</label><br>
        <input type="number" name="units_required" value="1" min="1" required><br><br>

        <label>Hospital Name:</label><br>
        <input type="text" name="hospital_name" placeholder="e.g. Dhaka Medical College" required><br><br>

        <label>Location / District:</label><br>
        <input type="text" name="location" placeholder="e.g. Mirpur, Dhaka" required><br><br>

        <label>Contact Number:</label><br>
        <input type="text" name="contact_number" required><br><br>

        <label>Medical Details / Reason:</label><br>
        <textarea name="medical_details" rows="3" cols="40"></textarea><br><br>

        <label>
            <input type="checkbox" name="is_anonymous" value="1"> Hide personal details (Anonymous Mode)
        </label><br><br>

        <button type="submit" style="padding: 10px 20px; background-color: #d9534f; color:white; border:none;">Publish Request</button>
    </form>
</body>
</html>