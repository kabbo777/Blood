<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../../controllers/CertificateController.php';

$code = $_GET['code'] ?? '';
$certCtrl = new CertificateController();
$cert = $certCtrl->getCertificateData($code);

if (!$cert) {
    die("Invalid or expired certificate code.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Official Donation Certificate - Smart Blood Network</title>
    <style>
        .cert-card { width: 750px; margin: 40px auto; padding: 40px; border: 10px solid #c0392b; background: #fff; font-family: 'Georgia', serif; text-align: center; box-shadow: 0 0 15px rgba(0,0,0,0.2); }
        .cert-header { font-size: 28px; color: #c0392b; font-weight: bold; text-transform: uppercase; }
        .cert-body { margin-top: 30px; font-size: 18px; line-height: 1.6; color: #2c3e50; }
        .donor-name { font-size: 26px; font-weight: bold; text-decoration: underline; color: #2c3e50; margin: 15px 0; }
        .cert-footer { margin-top: 40px; border-top: 2px solid #ccc; padding-top: 20px; font-size: 14px; color: #7f8c8d; }
    </style>
</head>
<body>
    <div class="cert-card">
        <div class="cert-header">Certificate of Appreciation</div>
        <p>This certificate is proudly presented to</p>
        <div class="donor-name"><?= htmlspecialchars($cert['donor_name']) ?></div>
        <div class="cert-body">
            For outstanding voluntary contribution by donating blood group <strong><?= htmlspecialchars($cert['blood_group']) ?></strong><br>
            at <strong><?= htmlspecialchars($cert['hospital_name']) ?></strong> (<?= htmlspecialchars($cert['district']) ?> District)<br>
            on <strong><?= htmlspecialchars($cert['donation_date']) ?></strong>.
        </div>
        <div class="cert-footer">
            Certificate ID: <strong><?= htmlspecialchars($cert['certificate_code']) ?></strong><br>
            Issued by Smart Blood Network System
        </div>
    </div>
    <div style="text-align:center;">
        <button onclick="window.print()" style="padding:10px 20px;background:#2c3e50;color:white;border:none;cursor:pointer;">Print Certificate</button>
    </div>
</body>
</html>