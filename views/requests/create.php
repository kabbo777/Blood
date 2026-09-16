<?php

require_once __DIR__ . '/../navbar.php';
?>
<div class="row justify-content-center">
    <div class="col-md-9 col-lg-7">
        <div class="card shadow-sm border-0 p-4">
            <h3 class="text-danger fw-bold mb-4 text-center">🩸 Post Blood / Platelet Requirement</h3>
            <form action="/smart_blood_network/requests/save" method="POST">

                <div class="mb-3">
                    <label class="form-label fw-semibold">Patient Name</label>
                    <input type="text" name="patient_name" class="form-control" required placeholder="Patient's full name">
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Blood Group Needed</label>
                        <select name="blood_group" class="form-select" required>
                            <?php foreach (['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg): ?>
                                <option value="<?= $bg ?>"><?= $bg ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <!-- BUG FIX: was 'requirement_type' missing from form -->
                        <label class="form-label fw-semibold">Requirement Type</label>
                        <select name="requirement_type" class="form-select">
                            <option value="Blood">Blood</option>
                            <option value="Platelets">Platelets</option>
                            <option value="Plasma">Plasma</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <!-- BUG FIX: field name was 'units_needed' → 'units_required' -->
                        <label class="form-label fw-semibold">Units Required</label>
                        <input type="number" name="units_required" value="1" min="1" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Hospital Name</label>
                    <input type="text" name="hospital_name" class="form-control" required placeholder="e.g. Square Hospital, Panthapath">
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Location / District</label>
                        <input type="text" name="location" class="form-control" required placeholder="e.g. Mirpur, Dhaka">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Urgency Level</label>
                        <select name="urgency_level" class="form-select">
                            <option value="Emergency_SOS">🚨 Emergency SOS</option>
                            <option value="High">🔴 High</option>
                            <option value="Medium" selected>🟡 Medium</option>
                            <option value="Low">🟢 Low</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Contact Number</label>
                    <input type="text" name="contact_number" class="form-control" required placeholder="+88017...">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Medical Details (optional)</label>
                    <textarea name="medical_details" class="form-control" rows="3"
                              placeholder="Any relevant notes for the donor or hospital..."></textarea>
                </div>

                <div class="form-check mb-4">
                    <input type="checkbox" name="is_anonymous" class="form-check-input" id="anonCheck">
                    <label class="form-check-label text-muted" for="anonCheck">
                        Anonymous mode — hide my personal details, share only medical necessity
                    </label>
                </div>

                <button type="submit" class="btn btn-danger w-100 fw-bold py-2">
                    🚨 Submit Emergency Request
                </button>
            </form>
        </div>
    </div>
</div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
