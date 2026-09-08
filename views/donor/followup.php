<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post-Donation Follow-up</title>
</head>
<body>
    <h2>Post-Donation Health Check-in</h2>
    <form action="/donor/followup/save" method="POST">
        <label for="donation_id">Donation Record ID:</label><br>
        <input type="number" id="donation_id" name="donation_id" required><br><br>

        <label for="wellbeing_score">How do you feel? (1 = Poor, 5 = Excellent):</label><br>
        <select id="wellbeing_score" name="wellbeing_score" required>
            <option value="5">5 - Excellent</option>
            <option value="4">4 - Good</option>
            <option value="3">3 - Fair</option>
            <option value="2">2 - Weak</option>
            <option value="1">1 - Unwell</option>
        </select><br><br>

        <label for="side_effects">Any symptoms or side effects (dizziness, bruising):</label><br>
        <textarea id="side_effects" name="side_effects" rows="4" cols="50"></textarea><br><br>

        <button type="submit">Submit Health Status</button>
    </form>
</body>
</html>