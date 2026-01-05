<?php

require_once 'auth.php';   
require_login();


if ($_SESSION['user']['role'] !== 'admin') {
    http_response_code(403);
    echo "Only admin can access this page.";
    exit;
}

require_once 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $role     = $_POST['role'] ?? 'user';
    $email    = $_POST['email'] ?? '';

    if ($username === '' || $password === '') {
        $message = "Username and password are required.";
    } else {
        
        $hash = password_hash($password, PASSWORD_BCRYPT);

        try {
            $db = get_db();
            $stmt = $db->prepare(
                'INSERT INTO users (username, password_hash, role, email)
                 VALUES (?, ?, ?, ?)'
            );
            $stmt->execute([$username, $hash, $role, $email]);
            $message = "User '$username' registered successfully!";
        } catch (PDOException $e) {
            $message = "Error: " . htmlspecialchars($e->getMessage());
        }
    }
}

$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Secure Web App – Register User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="app-container">
    <div class="card">
        <div class="top-bar">
            <div class="app-title">Secure Web-Database Portal</div>
            <div class="user-tag">
                Logged in as <strong><?php echo htmlspecialchars($user['username']); ?></strong>
                (admin)
            </div>
        </div>

        <h2>Register New User</h2>
        <p class="subtitle">
            Create test accounts for different roles (admin, user, guest) to demonstrate
            role-based access control.
        </p>

        <?php if ($message): ?>
            <div class="alert alert-info">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <label>
                Username<br>
                <input name="username" type="text">
            </label>

            <label>
                Email<br>
                <input name="email" type="email">
            </label>

            <label>
                Password<br>
                <input type="password" name="password">
            </label>

            <label>
                Role<br>
                <select name="role">
                    <option value="admin">admin</option>
                    <option value="user">user</option>
                    <option value="guest">guest</option>
                </select>
            </label>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Create User</button>
                <a href="dashboard.php" class="btn btn-secondary">Back to dashboard</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>