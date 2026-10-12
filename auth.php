
<?php
// auth.php — include on the FIRST line of every protected page
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

function is_admin() {
    return isset($_SESSION['role'])
        && $_SESSION['role'] === 'admin';
}

function require_admin() {
    if (!is_admin()) {
        http_response_code(403);
        exit('403 Forbidden: Admin access required.');
    }
}
?>