<?php
session_start();
$conn = include('config.php'); // Assuming this includes your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the user's email from the session
    if (isset($_SESSION['user_email'])) {
        $user_email = $_SESSION['user_email'];
    } else {
        // Handle the case where the user's email is not available
        echo "Error: User email not found in session.";
        exit;
    }

    // Retrieve the referring page URL
    $previousPage = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'default_page.php';

    // Retrieve form data
    $date = $_POST['date'];
    $end_date = $_POST['end_date']; // New field for end date
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $subject = $_POST['title'];
    $room = $_POST['place'];

    // Determine the day of the week for the start date
    $dayOfWeek = date('l', strtotime($date));

    // Prepare and bind parameters for insertion
    $stmt = $conn->prepare("INSERT INTO class_sched (start_time, end_time, date, end_date, subject, room, day_of_week, is_recurring, user_email) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $is_recurring = true; // Assuming this is a recurring event
    $stmt->bind_param("sssssssss", $start_time, $end_time, $date, $end_date, $subject, $room, $dayOfWeek, $is_recurring, $user_email);

    // Execute the statement
    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    // Redirect back to the referring page
    header("Location: $previousPage");
    exit;
}
?>
