<?php
// auth/session.php
session_start();

function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /auth/login.php");
        exit();
    }
}

function checkUserType($allowed_types) {
    if (!is_array($allowed_types)) {
        $allowed_types = [$allowed_types];
    }
    
    if (!isset($_SESSION['user_type']) || !in_array($_SESSION['user_type'], $allowed_types)) {
        header("Location: /auth/login.php");
        exit();
    }
}