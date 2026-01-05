<?php

require_once 'auth.php';     
require_once 'activity.php';  

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $user = find_user_by_username($username);

    if ($user && password_verify($password, $user['password_hash'])) {
        
        $_SESSION['user'] = [
            'id'       => $user['id'],
            'username' => $user['username'],
            'role'     => $user['role']
        ];

        log_activity(
            $user['id'],
            $user['username'],
            'login_success',
            'IP: ' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown')
        );

        header('Location: profile.php');
        exit;
    } else {
        $error = 'Invalid username or password.';

        log_activity(
            0,
            $username,
            'login_failed',
            'IP: ' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown')
        );
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Secure Web App – Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="app-container">
    <div class="card">
        <div class="top-bar">
            <div class="app-title">Secure Web-Database Portal</div>
        </div>

        <h2>Sign in</h2>
        <p class="subtitle">Use your test credentials (admin, user, or guest) to access the system.</p>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <label>
                Username<br>
                <input type="text" name="username" autocomplete="username">
            </label>

            <label>
                Password<br>
                <input type="password" name="password" autocomplete="current-password">
            </label>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
        </form>

        <p class="muted" style="margin-top:16px;">
            Demo accounts: admin1, user1, guest1 (with the passwords you created).
        </p>
    </div>
</div>
</body>
</html>