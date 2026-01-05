<?php
require_once 'auth.php';
require_login();

$user = $_SESSION['user'];
$role = $user['role'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Secure Web App – Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="app-container">
    <div class="card">
        <div class="top-bar">
            <div class="app-title">Secure Web-Database Portal</div>
            <div class="user-tag">
                Logged in as <strong><?php echo htmlspecialchars($user['username']); ?></strong>
                (<?php echo htmlspecialchars($role); ?>)
            </div>
        </div>

        <h2>Your Profile</h2>

        <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
        <p><strong>Role:</strong> <?php echo htmlspecialchars($role); ?></p>

        <?php if ($role === 'guest'): ?>
            <div class="alert alert-error">
                You are currently a <strong>guest</strong>. You do not have permission to submit or
                view any records.
            </div>
            <p>
                <a href="request_access.php" class="btn btn-primary">Request access upgrade</a>
            </p>

        <?php elseif ($role === 'user'): ?>
            <div class="alert alert-info">
                You can submit records that include both clear text and a sensitive field.
                The sensitive field is encrypted with AES-256 and only admins can view the decrypted
                value. When you view records, you will only see the clear-text part and the integrity
                (HMAC) status.
            </div>

        <?php elseif ($role === 'admin'): ?>
            <div class="alert alert-info">
                You have full administrative access. You can submit data, view both clear-text and
                sensitive fields, see the encrypted (ciphertext) values, register new users, view
                activity logs, and verify data integrity using the HMAC check.
            </div>
        <?php endif; ?>

        <div style="margin-top:16px; display:flex; justify-content:space-between; align-items:center;">
            <a href="dashboard.php" class="btn btn-secondary">Go to dashboard</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
</div>
</body>
</html>