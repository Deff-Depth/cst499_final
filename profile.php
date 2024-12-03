<?php
session_start();

// Check if the user is logged in, if not, redirect them to login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include 'db_connection.php';

// Fetch user data for the profile
$user_id = $_SESSION['user_id']; // Get user_id from session
try {
    // Using 'id' column in the WHERE clause instead of 'user_id'
    $user_sql = "SELECT * FROM users WHERE id = :user_id";
    $user_stmt = $conn->prepare($user_sql);
    $user_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $user_stmt->execute();
    $user_data = $user_stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user_data) {
        echo "Error: User not found.";
        exit;
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
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
    <title>User Profile</title>
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
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
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
            <h2 class="card-title mb-4">Welcome, <?php echo htmlspecialchars($user_data['username']); ?>!</h2>
            <div class="mb-3">
                <strong>Email:</strong> <?php echo htmlspecialchars($user_data['email']); ?>
            </div>
            <div class="mb-3">
                <strong>Member Since:</strong> <?php echo htmlspecialchars(date('F j, Y', strtotime($user_data['created_at']))); ?>
            </div>
            <a href="courses.php" class="btn btn-primary"><i class="fas fa-book-open"></i> View Courses</a>
            <a href="reset_password.php" class="btn btn-warning mt-2"><i class="fas fa-key"></i> Reset Password</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
$conn = null; // Close the connection
?>
