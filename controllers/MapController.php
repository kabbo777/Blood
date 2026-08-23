<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/MapLocation.php';

class MapController {
    private $db;
    private $mapModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $database = new Database();
        $this->db = $database->connect();
        $this->mapModel = new MapLocation($this->db);
    }

    public function getMapDataJson() {
        $markers = $this->mapModel->getAllMapMarkers();
        return json_encode($markers, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }
}