<?php
$hostname = "localhost";
$username = "root";
$password = "";
$database = "scheduler";

// Create connection
$conn = new mysqli($hostname, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assuming $user_email is defined somewhere before this code
$user_email = $conn->real_escape_string($user_email);

// Function to get count from query
function getCount($conn, $sql, $params) {
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param(...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = ($result->num_rows > 0) ? $result->fetch_assoc()['count'] : 0;
    $stmt->close();
    return $count;
}

// SQL for ongoing schedules in table_sched
$sql_ongoing_table_sched = "SELECT COUNT(*) AS count FROM table_sched 
    WHERE user_email = ? 
    AND available_status = 'Occupied' 
    AND (
        (start_time <= NOW() AND end_time >= NOW()) OR
        (start_time <= NOW() AND end_time >= ADDTIME(NOW(), '00:15:00')) OR
        (start_time >= NOW() AND start_time <= ADDTIME(NOW(), '00:15:00'))
    )";
$ongoing_table_sched = getCount($conn, $sql_ongoing_table_sched, ['s', $user_email]);

// SQL for pending schedules in table_sched
$sql_pending_table_sched = "SELECT COUNT(*) AS count FROM table_sched 
    WHERE user_email = ? 
    AND available_status = 'Available' 
    AND (
        DATE(start_time) = CURDATE() AND start_time > NOW() OR
        DATE(start_time) > CURDATE()
    )";
$pending_table_sched = getCount($conn, $sql_pending_table_sched, ['s', $user_email]);

// SQL for ongoing schedules in class_sched
$sql_ongoing_class_sched = "SELECT COUNT(*) AS count FROM class_sched 
    WHERE user_email = ? 
    AND available_status = 'Occupied'";
$ongoing_class_sched = getCount($conn, $sql_ongoing_class_sched, ['s', $user_email]);

// SQL for pending schedules in class_sched
$sql_pending_class_sched = "SELECT COUNT(*) AS count FROM class_sched 
    WHERE user_email = ? 
    AND available_status = 'Available' 
    AND (
        DATE(start_time) = CURDATE() AND start_time > NOW() OR
        DATE(start_time) > CURDATE()
    )";
$pending_class_sched = getCount($conn, $sql_pending_class_sched, ['s', $user_email]);

$total_pending = $pending_table_sched + $pending_class_sched;
$total_ongoing = $ongoing_table_sched + $ongoing_class_sched;

$conn->close();
?>
