<?php require_once __DIR__ . '/../navbar.php'; ?>
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm border-0 p-4">
            <h3 class="text-danger fw-bold mb-3 text-center">Post-Donation Health Check-in</h3>
            <form action="/smart_blood_network/donor/followup/save" method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Donation Record ID</label>
                    <input type="number" name="donation_id" class="form-control" required placeholder="e.g. 101">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">How do you feel? (1 = Poor, 5 = Excellent)</label>
                    <select name="wellbeing_score" class="form-select" required>
                        <option value="5">5 - Excellent (Feeling Great)</option>
                        <option value="4">4 - Good (Normal)</option>
                        <option value="3">3 - Fair (Slight Fatigue)</option>
                        <option value="2">2 - Weak (Dizzy/Tired)</option>
                        <option value="1">1 - Unwell (Need Medical Attention)</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Any side effects or notes?</label>
                    <textarea name="side_effects" class="form-control" rows="4" placeholder="Describe any bruising, dizziness, or symptoms..."></textarea>
                </div>
                <button type="submit" class="btn btn-danger w-100 fw-bold">Submit Health Status</button>
            </form>
        </div>
    </div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
