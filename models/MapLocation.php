<?php
class MapLocation {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllMapMarkers() {
        $markers = [];
        $donorSql = "SELECT id, full_name, role, blood_group, availability_status, anonymous_mode, location, latitude, longitude, phone 
                    FROM users 
                    WHERE latitude IS NOT NULL AND longitude IS NOT NULL AND latitude != 0 AND longitude != 0 AND role = 'donor'";
        
        $stmt = $this->conn->prepare($donorSql);
        $stmt->execute();
        $donors = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($donors as $donor) {
            $isAnon = (bool)$donor['anonymous_mode'];
            $markers[] = [
                'name' => $isAnon ? 'Anonymous Donor' : $donor['full_name'],
                'type' => 'Donor (' . $donor['blood_group'] . ')',
                'blood_group' => $donor['blood_group'],
                'status' => $donor['availability_status'],
                'location' => $donor['location'] ?? 'Location Registered',
                'latitude' => (float)$donor['latitude'],
                'longitude' => (float)$donor['longitude'],
                'contact' => $isAnon ? '[Hidden for Privacy]' : $donor['phone'],
                'category' => 'donor'
            ];
        }
        $facilityCheck = $this->conn->query("SHOW TABLES LIKE 'map_locations'");
        if ($facilityCheck && $facilityCheck->rowCount() > 0) {
            $facilitySql = "SELECT * FROM map_locations";
            $fStmt = $this->conn->prepare($facilitySql);
            $fStmt->execute();
            $facilities = $fStmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($facilities as $fac) {
                $markers[] = [
                    'name' => $fac['name'],
                    'type' => $fac['type'] ?? 'Hospital / Blood Bank',
                    'blood_group' => 'N/A',
                    'status' => 'Active Facility',
                    'location' => $fac['location'] ?? 'Dhaka',
                    'latitude' => (float)$fac['latitude'],
                    'longitude' => (float)$fac['longitude'],
                    'contact' => $fac['contact'] ?? 'Emergency Helpline',
                    'category' => 'facility'
                ];
            }
        } else {
            $markers[] = [
                'name' => 'Dhaka Medical College Hospital',
                'type' => 'Hospital & Central Blood Bank',
                'blood_group' => 'All Groups Available',
                'status' => '24/7 Open',
                'location' => 'Secretariat Road, Dhaka',
                'latitude' => 23.7258,
                'longitude' => 90.3977,
                'contact' => '+880255165088',
                'category' => 'facility'
            ];
            $markers[] = [
                'name' => 'Square Hospital Blood Bank',
                'type' => 'Blood Bank Facility',
                'blood_group' => 'Platelets & Whole Blood',
                'status' => '24/7 Open',
                'location' => 'West Panthapath, Dhaka',
                'latitude' => 23.7530,
                'longitude' => 90.3816,
                'contact' => '+8801713333337',
                'category' => 'facility'
            ];
        }
        return $markers;
    }
}