<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
include 'db_connection.php';

// Fetch user ID from the session
$user_id = $_SESSION['user_id'];

// Fetch enrolled courses for the user
$enrolled_sql = "SELECT c.id, c.course_name, c.instructor_name, c.start_date, c.end_date 
                 FROM registrations r 
                 JOIN courses c ON r.class_id = c.id 
                 WHERE r.user_id = :user_id";
$enrolled_stmt = $conn->prepare($enrolled_sql);
$enrolled_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$enrolled_stmt->execute();
$enrolled_courses = $enrolled_stmt->fetchAll(PDO::FETCH_ASSOC);

// Automatically add wildcards to the search query for partial matching
$search_query = isset($_POST['search']) ? '%' . trim($_POST['search']) . '%' : '%';

// Fetch available courses that have available seats and are not yet enrolled by the user
$search_sql = "
    SELECT * 
    FROM courses 
    WHERE course_name LIKE :search_term 
    AND max_enrollment > (SELECT COUNT(*) FROM registrations WHERE class_id = courses.id)
    AND id NOT IN (
        SELECT class_id 
        FROM registrations 
        WHERE user_id = :user_id
    )
    LIMIT 0, 25";

$stmt = $conn->prepare($search_sql);
$stmt->bindParam(':search_term', $search_query, PDO::PARAM_STR);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$available_courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Manage Courses</title>
    <style>
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

        body {
            margin-top: 80px; /* Ensure content starts below the navbar */
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

    <div class="container my-5">
        <h2 class="mb-4">Your Enrolled Courses</h2>
        <div class="row">
            <?php if (count($enrolled_courses) > 0): ?>
                <?php foreach ($enrolled_courses as $course): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($course['course_name']); ?></h5>
                                <p class="card-text"><strong>Instructor:</strong> <?php echo htmlspecialchars($course['instructor_name']); ?></p>
                                <p class="card-text"><strong>Start Date:</strong> <?php echo htmlspecialchars($course['start_date']); ?></p>
                                <p class="card-text"><strong>End Date:</strong> <?php echo htmlspecialchars($course['end_date']); ?></p>
                                <form action="remove_course.php" method="POST" class="mt-3">
                                    <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>"> <!-- Pass the course_id -->
                                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Remove</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">No enrolled courses found.</p>
            <?php endif; ?>
        </div>

        <h2 class="mt-5 mb-4">Available Courses</h2>
        <form method="POST" action="courses.php" class="form-inline mb-3 d-flex" onsubmit="saveScrollPosition()">
            <input type="text" name="search" class="form-control me-2" placeholder="Search for courses" value="<?php echo htmlspecialchars(isset($_POST['search']) ? $_POST['search'] : ''); ?>">
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
        </form>

        <div id="available-courses" class="row">
            <?php if (count($available_courses) > 0): ?>
                <?php foreach ($available_courses as $course): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($course['course_name']); ?></h5>
                                <p class="card-text"><strong>Instructor:</strong> <?php echo htmlspecialchars($course['instructor_name']); ?></p>
                                <p class="card-text"><strong>Start Date:</strong> <?php echo htmlspecialchars($course['start_date']); ?></p>
                                <p class="card-text"><strong>End Date:</strong> <?php echo htmlspecialchars($course['end_date']); ?></p>
                                <form action="register_course.php" method="POST">
                                    <input type="hidden" name="class_id" value="<?php echo $course['id']; ?>"> <!-- Pass the class_id -->
                                    <button type="submit" class="btn btn-success" <?php echo ($course['available_seats'] <= 0) ? 'disabled' : ''; ?>><i class="fas fa-plus"></i> Register</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">No available courses found.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function saveScrollPosition() {
            localStorage.setItem('scrollPosition', window.scrollY);
        }

        document.addEventListener("DOMContentLoaded", function() {
            if (localStorage.getItem('scrollPosition') !== null) {
                window.scrollTo(0, localStorage.getItem('scrollPosition'));
                localStorage.removeItem('scrollPosition');
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
$conn = null; // Close the connection
?>
