<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include 'db_connection.php';

// Handle password reset form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        $user_id = $_SESSION['user_id'];
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        try {
            // Update the user's password in the database
            $stmt = $conn->prepare("UPDATE users SET password = :password WHERE id = :user_id");
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();

            $success_message = "Password updated successfully.";
        } catch (PDOException $e) {
            $error_message = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Reset Password</title>
    <style>
        body {
            background: linear-gradient(120deg, #f0f0f0, #f8f9fa); /* Light gradient for a modern background */
        }

        .navbar {
            background: linear-gradient(to right, #007bff, #00c6ff); /* Vibrant blue gradient */
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
            border-radius: 15px; /* Rounded corners for a softer look */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Soft shadow for depth */
        }

        .card-title {
            color: #343a40; /* Dark grey for card titles */
        }

        .btn-primary {
            color: #fff; /* White text for buttons */
            background: linear-gradient(45deg, #007bff, #6a82fb); /* Blue gradient for primary buttons */
            border: none; /* Remove border for a cleaner look */
        }

        .btn-primary:hover {
            opacity: 0.85; /* Reduce opacity on hover */
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">The Goonies Online Academy</a>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php"><i class="fas fa-home"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="courses.php"><i class="fas fa-book"></i> Courses</a>
                    </li>
                    <li class="nav-item">
                        <form action="logout.php" method="POST" class="d-inline">
                            <button type="submit" class="btn btn-link nav-link" style="text-decoration: none;"><i class="fas fa-sign-out-alt"></i> Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="card p-4">
            <h2 class="card-title mb-4">Reset Password</h2>
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger"> <?php echo htmlspecialchars($error_message); ?> </div>
            <?php endif; ?>
            <?php if (isset($success_message)): ?>
                <div class="alert alert-success"> <?php echo htmlspecialchars($success_message); ?> </div>
            <?php endif; ?>
            <form action="reset_password.php" method="POST">
                <div class="mb-3">
                    <label for="new_password" class="form-label">New Password</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm New Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn btn-primary">Reset Password</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
$conn = null; // Close the connection
?>
