<?php
session_start();
include 'db_connection.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    try {
        // Find the user by verification token
        $stmt = $conn->prepare("SELECT * FROM users WHERE verification_token = :token");
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Update the user to set email_verified to true and remove the token
            $stmt = $conn->prepare("UPDATE users SET email_verified = 1, verification_token = NULL WHERE id = :user_id");
            $stmt->bindParam(':user_id', $user['id'], PDO::PARAM_INT);
            $stmt->execute();

            $_SESSION['success_message'] = "Your email has been verified! You can now log in.";
            header('Location: login.php');
            exit;
        } else {
            $_SESSION['error_message'] = "Invalid verification link.";
            header('Location: register.php');
            exit;
        }
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Database error: " . $e->getMessage();
        header('Location: register.php');
        exit;
    }
} else {
    $_SESSION['error_message'] = "No verification token provided.";
    header('Location: register.php');
    exit;
}
?>
