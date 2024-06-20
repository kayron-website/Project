<?php
session_start();
$conn = include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $eventId = $_POST['event_id'];
    $date = $_POST['editDate'];
    $time = $_POST['editTime']; // Assuming time format is HH:MM-HH:MM
    $title = $_POST['editTitle'];
    $place = $_POST['editPlace'];

    // Parse time
    list($start, $end) = explode('-', $time);
    $startTime = date('H:i:s', strtotime($start));
    $endTime = date('H:i:s', strtotime($end));

    // Prepare and bind
    $stmt = $conn->prepare("UPDATE class_sched SET start_time = ?, end_time = ?, date = ?, subject = ?, room = ? WHERE id = ?");
    $stmt->bind_param("sssssi", $startTime, $endTime, $date, $title, $place, $eventId);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Record updated successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    // Retrieve the referring page URL
    $previousPage = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'default_page.php';

    // Redirect back to the referring page
    header("Location: $previousPage");
    exit;
}
?>
