<?php

require_once __DIR__ . '/../config/Database.php';

class DonationModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Record a completed donation.
     * Returns the new donation ID.
     */
    public function create(array $data): int {
        $stmt = $this->db->prepare(
            "INSERT INTO `donations`
                (`donor_id`, `request_id`, `blood_group`, `units_donated`,
                 `donation_date`, `hospital_name`, `status`)
             VALUES
                (:donor_id, :request_id, :blood_group, :units_donated,
                 :donation_date, :hospital_name, 'Completed')"
        );
        $stmt->execute([
            ':donor_id'      => $data['donor_id'],
            ':request_id'    => $data['request_id'] ?? null,
            ':blood_group'   => $data['blood_group'],
            ':units_donated' => $data['units_donated'] ?? 1,
            ':donation_date' => $data['donation_date'] ?? date('Y-m-d'),
            ':hospital_name' => $data['hospital_name'],
        ]);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Total completed donations for a donor (used for badge calculation).
     */
    public function countByDonor(int $donorId): int {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM `donations` WHERE `donor_id` = ? AND `status` = 'Completed'"
        );
        $stmt->execute([$donorId]);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Most recent donation date — used to calculate cooldown remaining.
     */
    public function getLastDonationDate(int $donorId): ?string {
        $stmt = $this->db->prepare(
            "SELECT `donation_date` FROM `donations`
             WHERE `donor_id` = ? AND `status` = 'Completed'
             ORDER BY `donation_date` DESC LIMIT 1"
        );
        $stmt->execute([$donorId]);
        return $stmt->fetchColumn() ?: null;
    }

    /**
     * Full donation history for a donor, joined with the original request for context.
     */
    public function getByDonor(int $donorId): array {
        $stmt = $this->db->prepare(
            "SELECT d.*,
                    br.patient_name,
                    br.location     AS req_location,
                    br.urgency_level
             FROM `donations` d
             LEFT JOIN `blood_requests` br ON d.`request_id` = br.`id`
             WHERE d.`donor_id` = ?
             ORDER BY d.`donation_date` DESC"
        );
        $stmt->execute([$donorId]);
        return $stmt->fetchAll();
    }
}
