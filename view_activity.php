<?php
require_once 'auth.php';
require_role('admin');
require_once 'db.php';

$db = get_db();
$rows = $db->query("SELECT * FROM activity_log ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Secure Web App – Activity Log</title>
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

        <h2>Activity Monitoring Log</h2>
        <p class="subtitle">
            Monitoring user logins and data submissions between client and server.
        </p>

        <div class="table-wrap">
            <table>
                <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Action</th>
                    <th>Details</th>
                    <th>Time</th>
                </tr>
                <?php foreach ($rows as $r): ?>
                    <tr>
                        <td><?php echo $r['id']; ?></td>
                        <td><?php echo htmlspecialchars($r['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($r['username']); ?></td>
                        <td><?php echo htmlspecialchars($r['action']); ?></td>
                        <td><?php echo htmlspecialchars($r['details']); ?></td>
                        <td><?php echo htmlspecialchars($r['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <p style="margin-top:16px;">
            <a href="dashboard.php" class="btn btn-secondary">Back to dashboard</a>
        </p>
    </div>
</div>
</body>
</html>