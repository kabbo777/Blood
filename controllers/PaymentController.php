<?php
require_once __DIR__ . '/../models/PaymentModel.php';

class PaymentController extends Controller {
    public function checkout() {
        $this->render('payment/checkout');
    }

    public function process() {
        $this->requireRole(['donor', 'recipient', 'hospital_admin']);

        $amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_FLOAT);
        $method = filter_input(INPUT_POST, 'payment_method', FILTER_SANITIZE_SPECIAL_CHARS);
        $ref    = "TXN-" . strtoupper(uniqid());

        if ($amount && $method) {
            $paymentModel = new PaymentModel();
            $paymentModel->recordTransaction($_SESSION['user_id'], $amount, $method, $ref);
        }

        $this->render('payment/checkout', ['success' => "Payment of BDT {$amount} via {$method} completed! Reference: {$ref}"]);
    }
}