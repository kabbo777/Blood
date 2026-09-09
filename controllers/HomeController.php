<?php
class HomeController extends Controller {
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->render('home');
    }
}