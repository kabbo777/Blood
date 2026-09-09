<?php require_once __DIR__ . '/../navbar.php'; ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm p-4 mt-4 mb-5">
            <h3 class="text-danger text-center fw-bold mb-4">Register</h3>
            <form action="/smart_blood_network/register/process" method="POST">
                <div class="mb-3"><input type="text" name="name" class="form-control" placeholder="Full Name" required></div>
                <div class="mb-3"><input type="email" name="email" class="form-control" placeholder="Email Address" required></div>
                <div class="mb-3"><input type="password" name="password" class="form-control" placeholder="Password" required></div>
                <div class="mb-3"><input type="text" name="phone" class="form-control" placeholder="Phone Number" required></div>
                
                <div class="mb-3">
                    <select name="role" class="form-select" required>
                        <option value="donor">Donor</option>
                        <option value="recipient">Recipient</option>
                        <option value="hospital_admin">Hospital / Blood Bank</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <select name="blood_group" class="form-select">
                        <option value="A+">A+</option><option value="A-">A-</option>
                        <option value="B+">B+</option><option value="B-">B-</option>
                        <option value="O+">O+</option><option value="O-">O-</option>
                        <option value="AB+">AB+</option><option value="AB-">AB-</option>
                    </select>
                </div>

                <div class="mb-3"><input type="text" name="district" class="form-control" placeholder="District (e.g., Dhaka)" required></div>

                <!-- NEW: Manual Address Fields -->
                <h6 class="mt-4 fw-bold text-danger">Location Details (For Map)</h6>
                <div class="mb-2"><input type="text" name="road" class="form-control" placeholder="Road No. / Street Name / Apartment"></div>
                <div class="mb-2"><input type="text" name="area" class="form-control" placeholder="Area (e.g., Dhanmondi)" required></div>
                <div class="mb-2"><input type="text" name="city" class="form-control" placeholder="City (e.g., Dhaka)" required></div>
                <div class="mb-4"><input type="text" name="country" class="form-control" placeholder="Country" value="Bangladesh" required></div>

                <button type="submit" class="btn btn-danger w-100 fw-bold">Register</button>
            </form>
        </div>
    </div>
</div>
</div>
</body>
</html>