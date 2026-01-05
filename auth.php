<?php

session_start();
require_once 'db.php';

function find_user_by_username($username) {
    $db = get_db();
    $stmt = $db->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$username]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function access_denied_page($role = 'guest') {
    echo '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>Access Denied</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
    <div class="app-container">
        <div class="card">

            <h2>Access Denied</h2>

            <div class="alert alert-error">
                You do not have permission to access this page.
            </div>

            <p class="subtitle">
                Your current role: <strong>' . htmlspecialchars($role) . '</strong>
            </p>

            <div style="margin-top:16px; display:flex; justify-content:space-between; align-items:center;">
                <a href="dashboard.php" class="btn btn-secondary">Go to dashboard</a>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>

        </div>
    </div>
    </body>
    </html>
    ';
    exit;
}


function require_login() {
    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit;
    }
}


function require_role($role) {
    require_login();
    $user = $_SESSION['user'];

    if ($user['role'] !== $role) {
        access_denied_page($user['role']);
    }
}


function require_any_role($roles = []) {
    require_login();
    $user = $_SESSION['user'];

    if (!in_array($user['role'], $roles, true)) {
        access_denied_page($user['role']);
    }
}