<?php
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $newPassword = $_POST['new_password'];
    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

    try {
        // Check if the token is valid and not expired
        $stmt = $conn->prepare("SELECT * FROM users WHERE reset_token = :reset_token AND reset_token_expiry > NOW()");
        $stmt->bindParam(':reset_token', $token);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Update the user's password and clear the reset token
            $stmt = $conn->prepare("UPDATE users SET password = :password, reset_token = NULL, reset_token_expiry = NULL WHERE id = :user_id");
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':user_id', $user['id'], PDO::PARAM_INT);
            $stmt->execute();

            $_SESSION['success_message'] = "Password reset successful. You can now log in.";
            header('Location: login.php');
            exit;
        } else {
            $_SESSION['error_message'] = "Invalid or expired reset token.";
            header('Location: login.php');
            exit;
        }
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Database error: " . $e->getMessage();
        header('Location: reset_password.php?token=' . htmlspecialchars($token));
        exit;
    }
}
?>
