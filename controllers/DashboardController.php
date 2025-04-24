<?php
class DashboardController {
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
    
        require_once __DIR__ . '/../views/dashboard.php';
    }
}
