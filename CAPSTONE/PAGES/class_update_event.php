<?php
$conn = include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'];
    $start = $data['start'];
    $end = $data['end'];

    // Parse start and end times
    $startDateTime = new DateTime($start);
    $endDateTime = new DateTime($end);
    $startTime = $startDateTime->format('H:i:s');
    $endTime = $endDateTime->format('H:i:s');
    $date = $startDateTime->format('Y-m-d');
    $dayOfWeek = $startDateTime->format('l');

    // Update the event in the schedule
    $stmt = $conn->prepare("UPDATE class_sched SET start_time = ?, end_time = ?, date = ?, day_of_week = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $startTime, $endTime, $date, $dayOfWeek, $id);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Record updated successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

