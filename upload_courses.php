<?php
session_start();
include 'db_connection.php';

// List of sample instructor names
$instructors = ['John Doe', 'Jane Smith', 'Michael Johnson', 'Sarah Lee', 'David Brown'];

// Open the CSV file
if (($handle = fopen('courses.csv', 'r')) !== false) {
    // Skip the header row
    fgetcsv($handle);
    
    // Prepare the insert SQL query
    $insertQuery = "INSERT INTO courses (class_name, class_description, max_enrollment, start_date, end_date, instructor_name) 
                    VALUES (:class_name, :class_description, :max_enrollment, :start_date, :end_date, :instructor_name)";
    $stmt = $conn->prepare($insertQuery);

    while (($data = fgetcsv($handle)) !== false) {
        // Get course data from the CSV
        $class_name = $data[0];
        $class_description = $data[1];
        $max_enrollment = $data[2];
        
        // Generate random dates for start and end
        $start_date = generateRandomDate('2024-01-01', '2024-06-30');
        $end_date = generateRandomDate(date('Y-m-d', strtotime($start_date . ' +1 day')), '2024-12-31');
        
        // Random instructor
        $instructor_name = $instructors[array_rand($instructors)];
        
        // Bind parameters and execute the query
        $stmt->bindParam(':class_name', $class_name);
        $stmt->bindParam(':class_description', $class_description);
        $stmt->bindParam(':max_enrollment', $max_enrollment);
        $stmt->bindParam(':start_date', $start_date);
        $stmt->bindParam(':end_date', $end_date);
        $stmt->bindParam(':instructor_name', $instructor_name);
        
        $stmt->execute();
    }
    
    fclose($handle);
    echo "Courses uploaded successfully!";
} else {
    echo "Error: Could not open the CSV file.";
}

// Function to generate a random date between two dates
function generateRandomDate($start_date, $end_date) {
    $start_timestamp = strtotime($start_date);
    $end_timestamp = strtotime($end_date);
    $random_timestamp = mt_rand($start_timestamp, $end_timestamp);
    return date('Y-m-d', $random_timestamp);
}

?>
