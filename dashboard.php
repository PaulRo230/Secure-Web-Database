<?php

require_once 'auth.php';
require_login();

$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Secure Web App – Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="app-container">
    <div class="card">
        <div class="top-bar">
            <div class="app-title">Secure Web-Database Portal</div>
            <div class="user-tag">
                Logged in as <strong><?php echo htmlspecialchars($user['username']); ?></strong>
                (<?php echo htmlspecialchars($user['role']); ?>)
            </div>
        </div>

        <h2>Dashboard</h2>
        <p class="subtitle">
            Choose an action below. Access is enforced based on your role (admin, user, or guest).
        </p>

        <p>
            <a href="profile.php" class="btn btn-secondary">Back</a>
            <a href="submit_data.php" class="btn btn-primary">Submit new data</a>
            <a href="view_data.php" class="btn btn-secondary">View data</a>

            <?php if ($user['role'] === 'admin'): ?>
                <a href="register.php" class="btn btn-secondary">Register new user</a>
                <a href="view_activity.php" class="btn btn-secondary">View activity logs</a>
            <?php endif; ?>

            <a href="logout.php" class="btn btn-danger" style="float:right;">Logout</a>
        </p>
    </div>
</div>
</body>
</html>