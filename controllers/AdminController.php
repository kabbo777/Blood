<?php
require_once __DIR__ . '/../models/InventoryModel.php';

class AdminController extends Controller {
    public function inventory() {
        $this->requireRole(['hospital_admin']);
        
        $inventoryModel = new InventoryModel();
        $inventory = $inventoryModel->getInventoryByHospital($_SESSION['user_id']);

        $this->render('admin/inventory', ['inventory' => $inventory]);
    }

    public function updateInventory() {
        $this->requireRole(['hospital_admin']);

        $inventoryId = filter_input(INPUT_POST, 'inventory_id', FILTER_VALIDATE_INT);
        $units       = filter_input(INPUT_POST, 'units', FILTER_VALIDATE_INT);

        if ($inventoryId && $units !== false) {
            $inventoryModel = new InventoryModel();
            $inventoryModel->updateUnits($inventoryId, $units);
        }

        header("Location: /admin/inventory");
        exit();
    }
}