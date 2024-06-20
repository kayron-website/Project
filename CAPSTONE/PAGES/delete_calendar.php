<?php
// Connect to your database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "scheduler";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve event ID from POST data
$event_id = $_POST['event_id'];

// Prepare a statement to delete events with the specified event_id from both tables
$sql1 = "DELETE FROM calendar_event WHERE event_id = ?";
$sql2 = "DELETE FROM table_sched WHERE event_id = ?";

// Prepare and execute the statements for both tables
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("s", $event_id);
$stmt1->execute();

$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("s", $event_id);
$stmt2->execute();

// Check if deletion was successful in both tables
if ($stmt1->affected_rows > 0 || $stmt2->affected_rows > 0) {
    // If deletion was successful in at least one table, return success response
    $response['success'] = true;
} else {
    // If deletion failed in both tables, return error response
    $response['success'] = false;
}

// Close statements
$stmt1->close();
$stmt2->close();

// Close database connection
$conn->close();

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
