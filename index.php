<?php
session_start();
if (isset($_SESSION['user_id'])) {
    // If the user is already logged in, redirect them to their profile
    header('Location: profile.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Welcome to Course Management</title>
    <style>
        body {
            background: linear-gradient(120deg, #f0f0f0, #f8f9fa); /* Light gradient for a modern background */
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card {
            background-color: #ffffff; /* White background for cards */
            border: 1px solid #e0e0e0; /* Light border for definition */
            border-radius: 15px; /* Rounded corners for a softer look */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Soft shadow for depth */
            padding: 2rem;
        }

        .card-title {
            color: #343a40; /* Dark grey for card titles */
            font-weight: bold;
        }

        .btn-primary, .btn-success {
            color: #fff; /* White text for buttons */
            border: none; /* Remove border for a cleaner look */
        }

        .btn-primary {
            background: linear-gradient(45deg, #007bff, #6a82fb); /* Blue gradient for primary buttons */
        }

        .btn-success {
            background: linear-gradient(45deg, #28a745, #76c7c0); /* Green gradient for success buttons */
        }

        .btn-primary:hover, .btn-success:hover {
            opacity: 0.85; /* Slightly reduce opacity on hover */
        }
    </style>
</head>

<body>
    <div class="card text-center">
        <h1 class="card-title mb-4">Welcome to The Goonies Online Academy</h1>
        <p class="card-text mb-4">Never Say Die, Never Stop Learning!</p>
        <div class="d-flex justify-content-center">
            <a href="login.php" class="btn btn-primary me-3"><i class="fas fa-sign-in-alt"></i> Login</a>
            <a href="register.php" class="btn btn-success"><i class="fas fa-user-plus"></i> Register</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
