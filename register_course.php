<?php
// Updated register_course.php to check available seats and reduce redundancy
session_start();
include 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $class_id = $_POST['class_id'];

    try {
        // Check if the course has available seats
        $seats_stmt = $conn->prepare("SELECT available_seats FROM courses WHERE id = :class_id");
        $seats_stmt->bindParam(':class_id', $class_id);
        $seats_stmt->execute();
        $seats_row = $seats_stmt->fetch(PDO::FETCH_ASSOC);

        if ($seats_row && $seats_row['available_seats'] > 0) {
            // Check if user is already registered for the course
            $check_stmt = $conn->prepare("SELECT * FROM registrations WHERE user_id = :user_id AND class_id = :class_id");
            $check_stmt->bindParam(':user_id', $user_id);
            $check_stmt->bindParam(':class_id', $class_id);
            $check_stmt->execute();

            if ($check_stmt->rowCount() > 0) {
                echo "Error: You are already registered for this course.";
            } else {
                // Register user for the course
                $stmt = $conn->prepare("INSERT INTO registrations (user_id, class_id) VALUES (:user_id, :class_id)");
                $stmt->bindParam(':user_id', $user_id);
                $stmt->bindParam(':class_id', $class_id);

                if ($stmt->execute()) {
                    // Update available seats
                    $update_seats_stmt = $conn->prepare("UPDATE courses SET available_seats = available_seats - 1 WHERE id = :class_id");
                    $update_seats_stmt->bindParam(':class_id', $class_id);
                    $update_seats_stmt->execute();

                    header('Location: courses.php');
                    exit;
                } else {
                    echo "Error: Registration failed.";
                }
            }
        } else {
            echo "Error: No available seats in this course.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
