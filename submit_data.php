<?php

require_once 'auth.php';

require_any_role(['admin', 'user']);

$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Secure Web App – Submit Data</title>
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

        <h2>Submit Data</h2>
        <p class="subtitle">
            Enter a non-sensitive field and a sensitive field. The sensitive field will be encrypted
            with AES-256 and protected by an HMAC before being stored in the database.
        </p>

        <form method="POST" action="save_data.php">
            <label>
                Clear text (non-sensitive)<br>
                <input name="clear_text" type="text">
            </label>

            <label>
                Sensitive data (will be encrypted)<br>
                <input name="sensitive" type="text">
            </label>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>

        <p class="muted" style="margin-top:16px;">
            Example: clear text could be a description, while the sensitive field might be an ID
            or secret value.
        </p>
    </div>
</div>
</body>
</html>