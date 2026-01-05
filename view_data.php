<?php

require_once 'auth.php';
require_login();
require_once 'crypto.php';
require_once 'db.php';

$user = $_SESSION['user'];
$role = $user['role'];

$db = get_db();

$stmt = $db->query(
    'SELECT r.*, u.username AS owner_username
     FROM records r
     JOIN users u ON r.user_id = u.id
     ORDER BY r.id DESC'
);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Secure Web App – View Data</title>
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

        <h2>Stored Records</h2>

        <?php if ($role === 'guest'): ?>
            <p class="alert alert-error">
                As a <strong>guest</strong>, you are not allowed to view any records.
            </p>
            <p>
                <a href="dashboard.php" class="btn btn-secondary">Back to dashboard</a>
            </p>
        <?php else: ?>

            <p class="subtitle">
                Admin can see all fields, users see only clear text and integrity status.
                Any tampered data will be highlighted in red.
            </p>

            <div class="table-wrap">
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Owner</th>
                        <th>Clear Text</th>
                        <th>Sensitive (plaintext)</th>
                        <th>Encrypted value (ciphertext)</th>
                        <th>Integrity Status (HMAC)</th>
                    </tr>

                    <?php foreach ($rows as $row): ?>
                        <?php
                            $ciphertext   = $row['sensitive_ciphertext'];
                            $stored_hmac  = $row['sensitive_hmac'];
                            $hmac_ok = verify_hmac(
                                $row['clear_text'],
                                $row['sensitive_ciphertext'],
                                $row['sensitive_hmac']
                            );
                            $row_class    = $hmac_ok ? '' : 'row-tampered';

                            if ($role === 'admin') {
                                $clear_text_display = htmlspecialchars($row['clear_text']);

                                if ($hmac_ok) {
                                    $sens_plain = aes_decrypt($ciphertext);
                                    $sens_plain_display = htmlspecialchars($sens_plain);
                                } else {
                                    $sens_plain_display = '<span class="text-danger">INTEGRITY ERROR</span>';
                                }

                                $cipher_display = htmlspecialchars($ciphertext);
                            } elseif ($role === 'user') {
                                $clear_text_display = htmlspecialchars($row['clear_text']);
                                $sens_plain_display = '<span class="muted">*** (not allowed)</span>';
                                $cipher_display     = '<span class="muted">*** (not allowed)</span>';
                            } else {
                                $clear_text_display = '***';
                                $sens_plain_display = '***';
                                $cipher_display     = '***';
                            }

                            $status_badge_class = $hmac_ok ? 'badge-ok' : 'badge-tampered';
                            $status_text        = $hmac_ok ? 'OK' : 'TAMPERED';
                        ?>
                        <tr class="<?php echo $row_class; ?>">
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['owner_username']); ?></td>
                            <td><?php echo $clear_text_display; ?></td>
                            <td><?php echo $sens_plain_display; ?></td>
                            <td><?php echo $cipher_display; ?></td>
                            <td>
                                <span class="badge <?php echo $status_badge_class; ?>">
                                    <?php echo $status_text; ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>

            <p style="margin-top:16px;">
                <a href="dashboard.php" class="btn btn-secondary">Back to dashboard</a>
            </p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>