<?php
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

// Fetch ongoing schedules from the database
$sql_ongoing = "SELECT * FROM ongoing_schedules";
$result_ongoing = $conn->query($sql_ongoing);

$ongoing_output = '<table class="table" width="100%">
                        <caption>Ongoing Schedules</caption>
                        <thead>
                            <tr>
                                <th>TIME</th>
                                <th>DATE</th>
                                <th>ACTIVITY/SUBJECT</th>
                                <th>PLACE</th>
                            </tr>
                        </thead>
                        <tbody>';

if ($result_ongoing->num_rows > 0) {
    while ($row = $result_ongoing->fetch_assoc()) {
        $start_time_12hour = date("h:i A", strtotime($row["start_time"]));
        $formatted_date = date("F j, Y", strtotime($row["date"]));

        $ongoing_output .= "<tr>";
        $ongoing_output .= "<td><h4>" . $start_time_12hour . "</h4></td>";
        $ongoing_output .= "<td><h4>" . $formatted_date . "</h4></td>";
        $ongoing_output .= "<td><h4>" . $row["activity"] . "</h4></td>";
        $ongoing_output .= "<td><h4>" . $row["location"] . "</h4></td>";
        $ongoing_output .= "</tr>";
    }
} else {
    $ongoing_output .= "<tr><td colspan='4'>No ongoing schedules found</td></tr>";
}

$ongoing_output .= '</tbody></table>';

// Fetch pending schedules from the database
$sql_pending = "SELECT * FROM pending_schedules";
$result_pending = $conn->query($sql_pending);

$pending_output = '<table class="table" width="100%">
                        <caption>Pending Schedules</caption>
                        <thead>
                            <tr>
                                <th>TIME</th>
                                <th>DATE</th>
                                <th>ACTIVITY/SUBJECT</th>
                                <th>PLACE</th>
                            </tr>
                        </thead>
                        <tbody>';

if ($result_pending->num_rows > 0) {
    while ($row = $result_pending->fetch_assoc()) {
        $start_time_12hour = date("h:i A", strtotime($row["start_time"]));
        $formatted_date = date("F j, Y", strtotime($row["date"]));

        $pending_output .= "<tr>";
        $pending_output .= "<td><h4>" . $start_time_12hour . "</h4></td>";
        $pending_output .= "<td><h4>" . $formatted_date . "</h4></td>";
        $pending_output .= "<td><h4>" . $row["activity"] . "</h4></td>";
        $pending_output .= "<td><h4>" . $row["location"] . "</h4></td>";
        $pending_output .= "</tr>";
    }
} else {
    $pending_output .= "<tr><td colspan='4'>No pending schedules found</td></tr>";
}

$pending_output .= '</tbody></table>';

// Combine both ongoing and pending schedules HTML content
$output = '<div class="table-container" id="table1">' . $ongoing_output . '</div>';
$output .= '<div class="table-container" id="table2">' . $pending_output . '</div>';

// Close database connection
$conn->close();

// Return the combined HTML content
echo $output;
?>
