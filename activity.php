<?php
require_once 'db.php';

function log_activity($user_id, $username, $action, $details = '') {
    try {
        $db = get_db();
        $stmt = $db->prepare(
            'INSERT INTO activity_log (user_id, username, action, details)
             VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$user_id, $username, $action, $details]);
    } catch (Exception $e) {
        // optional: error_log("Activity log error: " . $e->getMessage());
    }
}
