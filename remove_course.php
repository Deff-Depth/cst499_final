<?php
// remove_course.php: Handles course removal for users
session_start();
include 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Check if a course ID was provided in the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['course_id'])) {
    $user_id = $_SESSION['user_id'];
    $course_id = $_POST['course_id'];  // Match the parameter name with the form field

    try {
        // Remove the user from the course in the registrations table
        $delete_sql = "DELETE FROM registrations WHERE user_id = :user_id AND class_id = :course_id"; // Using course_id here
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $delete_stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT); // Bind to course_id
        $delete_stmt->execute();

        // Increase the available seats for the course in the courses table
        $update_seats_sql = "UPDATE courses SET available_seats = available_seats + 1 WHERE id = :course_id"; // id in courses table
        $update_seats_stmt = $conn->prepare($update_seats_sql);
        $update_seats_stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT); // Bind to course_id
        $update_seats_stmt->execute();

        // Redirect back to the courses page
        header('Location: courses.php');
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";  // This message is triggered when no course_id is passed
}

$conn = null; // Close the connection
?>
