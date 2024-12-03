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
    <title>Login</title>
    <style>
        body {
            background: linear-gradient(120deg, #f0f0f0, #f8f9fa); /* Light gradient for modern background */
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center; /* Center horizontally */
            align-items: center; /* Center vertically */
            flex-direction: column;
        }

        .navbar {
            position: fixed; /* Fix the navbar at the top */
            top: 0;
            left: 0;
            width: 100%;
            background: linear-gradient(to right, #007bff, #00c6ff); /* Vibrant blue gradient */
            z-index: 1030; /* Ensure it's above other content */
        }

        .navbar-brand,
        .nav-link {
            color: #fff !important; /* White text for navbar items */
        }

        .nav-link:hover {
            color: #ffdd57 !important; /* Yellow color for hover */
        }

        .card {
            background-color: #ffffff; /* White background for cards */
            border: 1px solid #e0e0e0; /* Light border for definition */
            border-radius: 10px; /* Squared corners for a sharper look */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Soft shadow for depth */
            padding: 2rem;
            width: 100%;
            max-width: 400px;
            margin-top: 80px; /* Adjust for space below fixed navbar */
        }

        .card-title {
            color: #343a40; /* Dark grey for card titles */
            font-weight: bold;
        }

        .btn-primary {
            color: #fff; /* White text for buttons */
            background: linear-gradient(45deg, #007bff, #6a82fb); /* Blue gradient for primary buttons */
            border: none; /* Remove border for a cleaner look */
        }

        .btn-primary:hover {
            opacity: 0.85; /* Reduce opacity on hover */
        }

        .form-control {
            border-radius: 5px; /* Squared corners for form inputs */
            border: 1px solid #ced4da; /* Light border for form controls */
        }

        .form-control:focus {
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); /* Blue focus shadow for input fields */
            border-color: #007bff; /* Blue border on focus */
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">The Goonies Online Academy</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php"><i class="fas fa-home"></i> Home</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="card text-center">
        <h2 class="card-title mb-4">Login</h2>
        <form action="login_action.php" method="POST">
            <div class="form-group mb-3">
                <label for="login" class="form-label">Username or Email:</label>
                <input type="text" class="form-control" id="login" name="login" required>
            </div>
            <div class="form-group mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-sign-in-alt"></i> Login</button>
        </form>
        <p class="mt-3">Don't have an account? <a href="register.php">Register here</a>.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
