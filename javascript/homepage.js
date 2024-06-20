<?php
$hostname = "localhost";
$username = "root";
$password = "";
$database = "scheduler";

$conn = mysqli_connect($hostname, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Assuming $user_email is defined somewhere before this code

// Count ongoing schedules from table_sched
$sql = "SELECT COUNT(*) AS ongoing FROM table_sched 
        WHERE user_email = '$user_email' 
        AND available_status = 'Occupied' 
        AND (
            (start_time <= CURRENT_TIME AND end_time >= CURRENT_TIME) OR
            (start_time <= CURRENT_TIME AND end_time >= ADDTIME(CURRENT_TIME, '00:15:00')) OR
            (start_time >= CURRENT_TIME AND start_time <= ADDTIME(CURRENT_TIME, '00:15:00'))
        )";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $ongoing_table_sched = $row['ongoing'];
} else {
    $ongoing_table_sched = 0;
}

// Count pending schedules from table_sched
$sql = "SELECT COUNT(*) AS pending FROM table_sched 
        WHERE user_email = '$user_email' 
        AND available_status = 'Available' 
        AND (
            (start_time <= CURRENT_TIME AND end_time >= CURRENT_TIME) OR
            (start_time <= CURRENT_TIME AND end_time >= ADDTIME(CURRENT_TIME, '00:15:00')) OR
            (start_time >= CURRENT_TIME AND start_time <= ADDTIME(CURRENT_TIME, '00:15:00'))
        )";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $pending_table_sched = $row['pending'];
} else {
    $pending_table_sched = 0;
}

// Count ongoing schedules from class_sched
$sql = "SELECT COUNT(*) AS ongoing FROM class_sched WHERE user_email = '$user_email' AND available_status = 'Occupied'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $ongoing_class_sched = $row['ongoing'];
} else {
    $ongoing_class_sched = 0;
}

// Count pending schedules from class_sched
$sql = "SELECT COUNT(*) AS pending FROM class_sched WHERE user_email = '$user_email' AND available_status = 'Available'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $pending_class_sched = $row['pending'];
} else {
    $pending_class_sched = 0;
}

$total_pending = $pending_table_sched + $pending_class_sched;
$total_ongoing = $ongoing_table_sched + $ongoing_class_sched;

mysqli_close($conn);
?>
