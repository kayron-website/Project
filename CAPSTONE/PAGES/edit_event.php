<?php
session_start();
include('database_connection.php'); // Adjust the path to your db connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_id = $_POST['event_id'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $activity = $_POST['activity'];
    $agenda = $_POST['agenda'];
    $location = $_POST['location'];
    $user_email = $_SESSION['user_email'];

    $sql = "UPDATE calendar_event 
            SET start_time = ?, end_time = ?, activity = ?, agenda = ?, location = ?
            WHERE id = ? AND author = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssis", $start_time, $end_time, $activity, $agenda, $location, $event_id, $user_email);

    if ($stmt->execute()) {
        echo "Event updated successfully.";
    } else {
        echo "Error updating event: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
