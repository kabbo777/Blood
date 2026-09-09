<?php

require_once __DIR__ . '/../models/ScreenerModel.php';
require_once __DIR__ . '/../models/UserModel.php';

class ScreenerController extends Controller {

    public function index(): void {
        $this->render('screener/index');
    }

    public function check(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /smart_blood_network/screener");
            exit();
        }

        $age        = (int)   ($_POST['age']        ?? 0);
        $weight     = (float) ($_POST['weight']     ?? 0);
        $hemoglobin = (float) ($_POST['hemoglobin'] ?? 0);
        $infection  = isset($_POST['infection']);
        $tattoo     = isset($_POST['tattoo']);
        $medication = isset($_POST['medication']);

        // Delegate eligibility logic to the Model
        $screenerModel = new ScreenerModel();
        $result        = $screenerModel->evaluateEligibility(
            $age, $weight, $hemoglobin, $infection, $tattoo, $medication
        );

        // If eligible and user is logged in, update their health status
        if ($result['eligible'] && isset($_SESSION['user_id'])) {
            $userModel = new UserModel();
            $userModel->updateHealthStatus($_SESSION['user_id'], 1);
            $_SESSION['health_check_passed'] = 1;
        }

        // Re-render the same screener page with the result
        $this->render('screener/index', ['result' => $result]);
    }
}
