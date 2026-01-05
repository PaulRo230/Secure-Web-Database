<?php
require_once 'auth.php';
require_login();

$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Secure Web App – Request Access</title>
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

        <h2>Request Access Upgrade</h2>

        <p>Hello <strong><?php echo htmlspecialchars($user['username']); ?></strong>,</p>

        <?php if ($user['role'] !== 'guest'): ?>
            <div class="alert alert-info">
                You already have access to system features – no upgrade is needed.
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                Your request to upgrade from <strong>guest</strong> to <strong>user</strong> has been
                recorded (for demo purposes). In a real system, an administrator would review and
                approve this request.
            </div>
        <?php endif; ?>

        <p style="margin-top:16px;">
            <a href="profile.php" class="btn btn-secondary">Back to profile</a>
            <a href="dashboard.php" class="btn btn-secondary">Back to dashboard</a>
        </p>
    </div>
</div>
</body>
</html>