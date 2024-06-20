<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    http_response_code(403); // Forbidden
    exit("Unauthorized access");
}

$userEmail = $_SESSION['user_email'];

// Include database configuration and ensure the connection is properly returned
include('config.php');

if (!isset($conn) || $conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, start_time, end_time, date, end_date, subject AS title, room AS place, day_of_week, is_recurring 
        FROM class_sched 
        WHERE user_email = ? 
        AND (date >= CURDATE() OR end_date >= CURDATE())";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("s", $userEmail);
if (!$stmt->execute()) {
    die("Execute failed: " . $stmt->error);
}

$result = $stmt->get_result();

$events = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $eventStartDate = new DateTime($row['date'] . ' ' . $row['start_time']);
        $eventEndDate = new DateTime($row['end_date'] . ' ' . $row['end_time']);

        if ($row['is_recurring']) {
            // Generate recurring events within the start_date to end_date range
            $startDate = new DateTime($row['date']);
            $endDate = new DateTime($row['end_date']);
            $interval = new DateInterval('P1D'); // 1 day interval
            $period = new DatePeriod($startDate, $interval, $endDate->modify('+1 day')); // Include the end date

            foreach ($period as $date) {
                if ($date->format('l') == $row['day_of_week']) {
                    $recurringEventStartDate = new DateTime($date->format('Y-m-d') . ' ' . $row['start_time']);
                    $recurringEventEndDate = new DateTime($date->format('Y-m-d') . ' ' . $row['end_time']);
                    if ($recurringEventStartDate >= new DateTime() || $recurringEventEndDate >= new DateTime()) {
                        $events[] = [
                            'id' => $row['id'],
                            'title' => $row['title'],
                            'place' => $row['place'],
                            'start' => $recurringEventStartDate->format('Y-m-d\TH:i:s'),
                            'end' => $recurringEventEndDate->format('Y-m-d\TH:i:s')
                        ];
                    }
                }
            }
        } else {
            if ($eventStartDate >= new DateTime() || $eventEndDate >= new DateTime()) {
                $events[] = [
                    'id' => $row['id'],
                    'title' => $row['title'],
                    'place' => $row['place'],
                    'start' => $eventStartDate->format('Y-m-d\TH:i:s'),
                    'end' => $eventEndDate->format('Y-m-d\TH:i:s')
                ];
            }
        }
    }
}

// Set the content type to JSON and output the result
header('Content-Type: application/json');
echo json_encode($events);

$stmt->close();
$conn->close();
?>
