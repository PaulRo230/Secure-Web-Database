<?php

require_once 'auth.php';
require_any_role(['admin', 'user']);   

require_once 'crypto.php';    
require_once 'db.php';
require_once 'activity.php'; 

$user = $_SESSION['user'];

$status  = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clear = trim($_POST['clear_text'] ?? '');
    $sens  = trim($_POST['sensitive'] ?? '');

    if ($clear === '' || $sens === '') {
        $status  = 'error';
        $message = "Both clear text and sensitive fields are required.";
    } else {
        
        $ciphertext = aes_encrypt($sens);
        $hmac = compute_hmac($clear, $ciphertext);

        try {
            $db = get_db();
            $stmt = $db->prepare(
                'INSERT INTO records (user_id, clear_text, sensitive_ciphertext, sensitive_hmac)
                 VALUES (?, ?, ?, ?)'
            );
            $stmt->execute([
                $user['id'],
                $clear,
                $ciphertext,
                $hmac
            ]);

            $newId = $db->lastInsertId();

            $details = "Record ID: $newId; Clear text (first 100 chars): " .
                       substr($clear, 0, 100) .
                       "; Sensitive length: " . strlen($sens);

            log_activity(
                $user['id'],
                $user['username'],
                'data_submitted',
                $details
            );

            $status  = 'success';
            $message = "Data saved successfully with ID: " . htmlspecialchars($newId);
        } catch (PDOException $e) {
            $status  = 'error';
            $message = "Error saving data: " . htmlspecialchars($e->getMessage());
        }
    }
} else {
    header('Location: submit_data.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Secure Web App – Save Data</title>
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

        <h2>Save Data Result</h2>

        <?php if ($status === 'success'): ?>
            <div class="alert alert-info">
                <?php echo $message; ?>
            </div>
            <p class="subtitle">
                Your clear text and encrypted sensitive data have been stored in the database.
                The sensitive field is protected with AES-256 encryption and an HMAC for integrity.
            </p>
        <?php elseif ($status === 'error'): ?>
            <div class="alert alert-error">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <p style="margin-top:16px;">
            <a href="submit_data.php" class="btn btn-primary">Submit more data</a>
            <a href="view_data.php" class="btn btn-secondary">View stored records</a>
            <a href="dashboard.php" class="btn btn-secondary">Back to dashboard</a>
        </p>
    </div>
</div>
</body>
</html>