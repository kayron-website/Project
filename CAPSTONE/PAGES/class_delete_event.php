<?php
$conn = include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $eventId = $_POST['event_id'];

    // Prepare and bind
    $stmt = $conn->prepare("DELETE FROM class_sched WHERE id = ?");
    $stmt->bind_param("i", $eventId);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Event deleted successfully";
       
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

   
}
?>
