<?php
session_start();

if (isset($_SESSION['user_email'])) {
    $user_email = $_SESSION['user_email'];
    $allowed_roles = array("Implementor");
    $user_type = $_SESSION['user_type'];

    if (!in_array($user_type, $allowed_roles)) {
        header("Location: unauthorized.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}

// Retrieve the data sent from JavaScript
$data = json_decode(file_get_contents('php://input'), true);

// Check if 'category' key exists in $data
$category = isset($data['category']) ? $data['category'] : null;

// Extract other event details
$date = $data['date'];
$endDate = $data['end_date'];
$startTime = $data['start_time'];
$endTime = $data['end_time'];
$location = $data['location'];
$activity = $data['activity'];
$selectedEmails = $data['selected_emails'];
$agenda = isset($data['agenda']) ? $data['agenda'] : '';

// Handle uploaded file
$attachmentName = isset($_FILES['attachment']['name']) ? $_FILES['attachment']['name'] : null;
$attachmentTmpName = isset($_FILES['attachment']['tmp_name']) ? $_FILES['attachment']['tmp_name'] : null;
$attachmentData = null;

// Check if file was uploaded successfully
if (!empty($attachmentTmpName) && is_uploaded_file($attachmentTmpName)) {
    // Read file content
    $attachmentData = file_get_contents($attachmentTmpName);
}

// Your database connection and insertion logic here (adjust accordingly)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "scheduler";

$conn = new mysqli($servername, $username, $password, $dbname);

// Insert into calendar_page table
$calendarPageQuery = "INSERT INTO calendar_page (date, end_date, start_time, end_time, location, activity) VALUES (?, ?, ?, ?, ?, ?)";
$calendarPageStatement = $conn->prepare($calendarPageQuery);
$calendarPageStatement->bind_param("ssssss", $date, $endDate, $startTime, $endTime, $location, $activity);
$calendarPageStatement->execute();

// Function to handle insertion into table_sched and calendar_event
function insertSchedule($conn, $table, $eventId, $startTime, $endTime, $date, $activity, $location, $userEmail, $agenda = null, $endDate = null)
{
    if ($table == 'calendar_event') {
        // Modify the query to include the 'agenda' field and 'event_id'
        $query = "INSERT INTO $table (event_id, start_time, end_time, date, end_date, activity, location, user_email, author, agenda) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    } else {
        $query = "INSERT INTO $table (event_id, start_time, end_time, date, activity, location, user_email, agenda) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    }

    // Get author from user_email session variable
    $author = $_SESSION['user_email'];

    $statement = $conn->prepare($query);

    if ($table == 'calendar_event') {
        // Adjust the binding parameters to include 'agenda' and 'event_id'
        $statement->bind_param("ssssssssss", $eventId, $startTime, $endTime, $date, $endDate, $activity, $location, $userEmail, $author, $agenda);
    } else {
        $statement->bind_param("ssssssss", $eventId, $startTime, $endTime, $date, $activity, $location, $userEmail, $agenda);
    }

    $statement->execute();
}

// Generate a unique event ID
$eventId = uniqid('event_');

// If 'select-all' checkbox is selected
if ($category === 'select-all') {
    // Select all distinct user emails from the database
    $selectAllEmailsQuery = "SELECT DISTINCT user_email FROM user_form";
    $selectAllEmailsResult = $conn->query($selectAllEmailsQuery);

    if ($selectAllEmailsResult->num_rows > 0) {
        // Insert into both table_sched and calendar_event with agenda and the same event_id for all users
        while ($row = $selectAllEmailsResult->fetch_assoc()) {
            $userEmail = $row["user_email"];

            // Insert into table_sched
            insertSchedule($conn, 'table_sched', $eventId, $startTime, $endTime, $date, $endDate, $activity, $location, $userEmail, $agenda);

            // Insert into calendar_event
            insertSchedule($conn, 'calendar_event', $eventId, $startTime, $endTime, $date, $activity, $location, $userEmail, $agenda, $endDate);
        }
    }
} else {
    // Handle individual selected emails
    foreach ($selectedEmails as $userEmail) {
        // Insert into both table_sched and calendar_event with agenda and the same event_id for all users
        insertSchedule($conn, 'table_sched', $eventId, $startTime, $endTime, $date, $activity, $location, $userEmail, $agenda);

        // Insert into calendar_event
        insertSchedule($conn, 'calendar_event', $eventId, $startTime, $endTime, $date, $activity, $location, $userEmail, $agenda, $endDate);
    }
}

// Check if any selected user has a schedule at the specified time
$selectedUsersWithSchedule = [];

foreach ($selectedEmails as $userEmail) {
    // Check for overlapping schedules in class_sched
    $checkOverlapQuery = "SELECT * FROM class_sched 
                          WHERE user_email = ? 
                          AND date = ? 
                          AND ((start_time <= ? AND end_time > ?) OR (start_time < ? AND end_time >= ?))";
    $checkOverlapStatement = $conn->prepare($checkOverlapQuery);
    $checkOverlapStatement->bind_param("ssssss", $userEmail, $date, $endTime, $startTime, $endTime, $startTime);
    $checkOverlapStatement->execute();
    $result = $checkOverlapStatement->get_result();

    if ($result->num_rows > 0) {
        // User has an overlapping schedule
        $selectedUsersWithSchedule[] = $userEmail;
    }
}

// Prepare a message based on the check result
if (!empty($selectedUsersWithSchedule)) {
    $message = "The following Attendee(s) have a schedule at the selected time: " . implode(', ', $selectedUsersWithSchedule);
    $status = 'error';
} else {
    // Check if any user is selected
    if (empty($selectedEmails)) {
        $message = "No attendee selected.";
        $status = 'error';
    } else {
        $message = "Selected attendees have no schedule at the selected time and date.";
        $status = 'success';
    }
}

// Log the final status, message, and selected users for debugging
$logMessage = "Final Status: $status, Message: $message, Selected Users: " . implode(', ', $selectedUsersWithSchedule);
error_log($logMessage);

// Return a plain text response
echo $message;

$conn->close();
?>
