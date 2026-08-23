<?php
class DonorMatching {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    #Filter Donors by Blood Group, Location, and Last Donation Date
    public function filterDonors($bloodGroup = '', $location = '', $maxLastDonationDate = '') {
        $sql = "SELECT u.id, u.full_name, u.email, u.phone, u.blood_group, u.location, u.availability_status, u.reliability_score,
                       MAX(d.donation_date) as last_donation_date
                FROM users u
                LEFT JOIN donation_records d ON u.id = d.donor_id
                WHERE u.role = 'donor'";
        
        $params = [];

        if (!empty($bloodGroup)) {
            $sql .= " AND u.blood_group = :blood_group";
            $params[':blood_group'] = $bloodGroup;
        }

        if (!empty($location)) {
            $sql .= " AND u.location LIKE :location";
            $params[':location'] = '%' . $location . '%';
        }

        $sql .= " GROUP BY u.id";

        if (!empty($maxLastDonationDate)) {
            $sql .= " HAVING last_donation_date IS NULL OR last_donation_date <= :max_date";
            $params[':max_date'] = $maxLastDonationDate;
        }

        $sql .= " ORDER BY u.availability_status ASC, u.reliability_score DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    # AI Donor-Recipient Match Scoring Algorithm
    public function calculateAIMatches($recipientBloodGroup, $reqLat, $reqLng) {
        $sql = "SELECT u.id, u.full_name, u.phone, u.blood_group, u.location, u.latitude, u.longitude, 
                       u.availability_status, u.reliability_score, MAX(d.donation_date) as last_donation
                FROM users u
                LEFT JOIN donation_records d ON u.id = d.donor_id
                WHERE u.role = 'donor' AND u.availability_status != 'Unavailable'
                GROUP BY u.id";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $donors = $stmt->fetchAll();

        $rankedDonors = [];

        foreach ($donors as $donor) {
           
            $compatScore = $this->getBloodCompatibilityScore($donor['blood_group'], $recipientBloodGroup);
            if ($compatScore == 0) continue; 

            $distanceKm = $this->haversineDistance($reqLat, $reqLng, $donor['latitude'], $donor['longitude']);
            $proximityScore = max(0, 40 - ($distanceKm * 2)); 
            $reliabilityScore = ($donor['reliability_score'] / 5.0) * 20;
            $totalMatchScore = min(100, round($compatScore + $proximityScore + $reliabilityScore));
            $donor['distance_km'] = round($distanceKm, 2);
            $donor['match_score'] = $totalMatchScore;
            $donor['compat_score'] = $compatScore;
            $donor['proximity_score'] = round($proximityScore);
            $rankedDonors[] = $donor;
        }

        usort($rankedDonors, function($a, $b) {
            return $b['match_score'] <=> $a['match_score'];
        });

        return $rankedDonors;
    }

    private function getBloodCompatibilityScore($donorGroup, $recipientGroup) {
        $compatibility = [
            'O-'  => ['O-', 'O+', 'A-', 'A+', 'B-', 'B+', 'AB-', 'AB+'],
            'O+'  => ['O+', 'A+', 'B+', 'AB+'],
            'A-'  => ['A-', 'A+', 'AB-', 'AB+'],
            'A+'  => ['A+', 'AB+'],
            'B-'  => ['B-', 'B+', 'AB-', 'AB+'],
            'B+'  => ['B+', 'AB+'],
            'AB-' => ['AB-', 'AB+'],
            'AB+' => ['AB+']
        ];

        if ($donorGroup === $recipientGroup) {
            return 40; 
        }
        if (isset($compatibility[$donorGroup]) && in_array($recipientGroup, $compatibility[$donorGroup])) {
            return 30; 
        }
        return 0; 
    }

    private function haversineDistance($lat1, $lon1, $lat2, $lon2) {
        $earthRadius = 6371; 
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return $earthRadius * $c;
    }
}