<?php
// admin_helpers.php

require_once 'init_session.php';

function hasNewInquiries($pdo) {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        return false; 
    }

    if (!isset($_SESSION['last_notification_view_time'])) {

        try {
            $sql = "SELECT COUNT(*) FROM messages";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Error checking initial inquiry count for badge: " . $e->getMessage());
            return false; 
        }
    }

    $last_view_time = $_SESSION['last_notification_view_time'];

    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM messages WHERE created_at > ?");
        $stmt->execute([$last_view_time]);
        return $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        error_log("Error checking new inquiries for badge: " . $e->getMessage());
        return false; 
    }
}
?>
