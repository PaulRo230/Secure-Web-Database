<?php
session_start();
require_once 'activity.php';  

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];

    log_activity(
        $user['id'],
        $user['username'],
        'logout',
        'User logged out successfully'
    );
}

session_unset();
session_destroy();

header('Location: login.php');
exit;