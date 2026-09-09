<?php

require_once __DIR__ . '/../models/InventoryModel.php';

class AdminController extends Controller {

    public function inventory(): void {
        $this->requireRole(['donor', 'recipient', 'hospital_admin', 'admin']);

        
        $canManage = in_array($_SESSION['role'] ?? '', ['hospital_admin', 'admin'], true);

        $invModel  = new InventoryModel();
        $inventory = $invModel->getAllInventory();

        $this->render('admin/inventory', [
            'inventory' => $inventory,
            'canManage' => $canManage,
        ]);
    }

    
    public function updateInventory(): void {
        $this->requireRole(['hospital_admin', 'admin']);

        $inventoryId = filter_input(INPUT_POST, 'inventory_id', FILTER_VALIDATE_INT);
        $units       = filter_input(INPUT_POST, 'units',        FILTER_VALIDATE_INT);

        if ($inventoryId && $units !== false) {
            $invModel = new InventoryModel();
            $invModel->updateUnits($inventoryId, $units);
        }

        header("Location: /smart_blood_network/admin/inventory");
        exit();
    }

    
    public function addInventory(): void {
        $this->requireRole(['hospital_admin', 'admin']);

        $hospitalId  = filter_input(INPUT_POST, 'hospital_id',  FILTER_VALIDATE_INT);
        $bloodGroup  = filter_input(INPUT_POST, 'blood_group',  FILTER_SANITIZE_SPECIAL_CHARS);
        $units       = filter_input(INPUT_POST, 'units',        FILTER_VALIDATE_INT);
        $expiryDate  = filter_input(INPUT_POST, 'expiry_date',  FILTER_SANITIZE_SPECIAL_CHARS);

        if ($hospitalId && $bloodGroup && $units && $expiryDate) {
            $invModel = new InventoryModel();
            $invModel->addInventory($hospitalId, $bloodGroup, $units, $expiryDate);
        }

        header("Location: /smart_blood_network/admin/inventory");
        exit();
    }
}
