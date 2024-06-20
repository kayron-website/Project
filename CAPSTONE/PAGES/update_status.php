<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "scheduler";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get parameters from the GET request after sanitizing them
$email = $conn->real_escape_string($_GET['email']);

// Retrieve the current status from the database
$stmt = $conn->prepare("SELECT status FROM user_form WHERE user_email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($currentStatus);
$stmt->fetch();
$stmt->close();

// Determine the new status based on the current status
$newStatus = ($currentStatus == 1) ? 0 : 1;

// Prepare and bind the UPDATE statement
$stmt = $conn->prepare("UPDATE user_form SET status = ? WHERE user_email = ?");
$stmt->bind_param("ss", $newStatus, $email);

// Execute the statement
if ($stmt->execute()) {
    echo "Status updated successfully";
} else {
    echo "Error updating status: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
