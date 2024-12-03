<?php
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    try {
        // Check if the email is already registered
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $_SESSION['error_message'] = "Email already registered.";
            header('Location: register.php');
            exit;
        }

        // Insert new user with email verification token
        $verificationToken = bin2hex(random_bytes(16)); // Generate a unique token
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, email_verified, verification_token) 
                                VALUES (:username, :email, :password, 0, :verification_token)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':verification_token', $verificationToken);
        $stmt->execute();

        // Instead of sending an email, display the verification link
        $_SESSION['verification_link'] = "http://localhost:8080/verify_email.php?token=" . $verificationToken;
        $_SESSION['success_message'] = "Registration successful! Please click the verification link to activate your account.";
        header('Location: registration_success.php');
        exit;

    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Database error: " . $e->getMessage();
        header('Location: register.php');
        exit;
    }
}
?>
