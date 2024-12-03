<?php
session_start();

include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if 'login' and 'password' keys exist in $_POST
    if (!isset($_POST['login']) || !isset($_POST['password'])) {
        $_SESSION['error_message'] = "Login and password are required.";
        header('Location: login.php');
        exit;
    }

    $login = $_POST['login']; // This will be either username or email
    $password = $_POST['password'];

    try {
        // Check if the input is an email or username
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            // If it's an email, query by email
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam(':email', $login);
        } else {
            // If it's a username, query by username
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindParam(':username', $login);
        }

        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if user exists and the password is correct
        if ($user && password_verify($password, $user['password'])) {
            // Check if the user's email has been verified
            if ($user['email_verified'] == 0) {
                $_SESSION['error_message'] = "Please verify your email address before logging in.";
                header('Location: login.php');
                exit;
            }

            // Regenerate session ID to prevent session fixation
            session_regenerate_id(true);

            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];

            // Redirect to the profile page after successful login
            header('Location: profile.php');
            exit;
        } else {
            $_SESSION['error_message'] = "Incorrect username/email or password.";
            header('Location: login.php');
            exit;
        }
    } catch (PDOException $e) {
        // Handle database errors
        $_SESSION['error_message'] = "Database error: " . $e->getMessage();
        header('Location: login.php');
        exit;
    }
}
?>
