<?php
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    try {
        // Check if the email exists in the database
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Generate a unique reset token and expiration time (valid for 1 hour)
            $resetToken = bin2hex(random_bytes(16));
            $expiryTime = date("Y-m-d H:i:s", strtotime('+1 hour'));

            // Store the token and expiry in the database
            $stmt = $conn->prepare("UPDATE users SET reset_token = :reset_token, reset_token_expiry = :expiry_time WHERE email = :email");
            $stmt->bindParam(':reset_token', $resetToken);
            $stmt->bindParam(':expiry_time', $expiryTime);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            // Simulate sending a reset link (display it instead)
            $_SESSION['success_message'] = "A password reset link has been generated. Please click the link below to reset your password:";
            $_SESSION['reset_link'] = "http://localhost:8080/reset_password.php?token=" . $resetToken;

            header('Location: forgot_password.php');
            exit;
        } else {
            $_SESSION['error_message'] = "No account found with that email address.";
            header('Location: forgot_password.php');
            exit;
        }
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Database error: " . $e->getMessage();
        header('Location: forgot_password.php');
        exit;
    }
}
?>
