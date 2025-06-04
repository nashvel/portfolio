<?php
// admin_helpers.php

require_once 'init_session.php';

function hasNewInquiries($pdo) {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        return false; // Not logged in, no notifications to show
    }

    if (!isset($_SESSION['last_notification_view_time'])) {
        // If never visited notifications page, or session expired, assume all are new
        // Or, more conservatively, check if there are ANY inquiries.
        // For a badge, it's common to show it if there's anything potentially unseen.
        try {
            $sql = "SELECT COUNT(*) FROM messages";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Error checking initial inquiry count for badge: " . $e->getMessage());
            return false; // Error, don't show badge
        }
    }

    $last_view_time = $_SESSION['last_notification_view_time'];

    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM messages WHERE created_at > ?");
        $stmt->execute([$last_view_time]);
        return $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        error_log("Error checking new inquiries for badge: " . $e->getMessage());
        return false; // Error, don't show badge
    }
}
?>
