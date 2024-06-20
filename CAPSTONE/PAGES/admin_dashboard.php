<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "scheduler";

session_start();

if (isset($_SESSION['user_email'])) {
    $user_email = $_SESSION['user_email'];
    $allowed_roles = array("Admin");
    $user_type = $_SESSION['user_type'];

    if (!in_array($user_type, $allowed_roles)) {
        header("Location: unauthorized.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}

include('javascript/homepage.js');

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_email = $_SESSION['user_email']; // Retrieve the logged-in user's email

$sql_table_sched = "SELECT start_time, date, activity, location FROM table_sched WHERE user_email = '$user_email'";
$result_table_sched = $conn->query($sql_table_sched);

// Query to fetch data from class_sched
$sql_class_sched = "SELECT start_time, date, subject, room
                    FROM class_sched 
                    WHERE user_email = '$user_email'";
$result_class_sched = $conn->query($sql_class_sched);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scheduler</title>
    <link rel="icon" type="image/x-icon" href="img/logo.png">
    <link rel="stylesheet" href="css/font_1.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="practices/style.css">
    <style>
        .main {
            background: #f2f2f2;
        }
        body {
            background: #2a2a2a;
        }
    </style>
</head>
<body>
    <div class="web">
        <div class="wrapper">
            <nav class="nav">
                <div class="nav-logo" style="margin-top: 3px;">
                    <p>SCHEDULER</p>
                </div>
                <div class="nav-menu" id="navMenu">
                    <ul>
                        <li><a href="admin_calendar.php" class="link">Calendar</a></li>
                        <li><div class="dropdown">
                            <a href="#" class="link" onclick="toggleDropdown()">Settings</a>
                            <div id="dropdownMenu" class="dropdown-content">
                                <a href="admin_register.php">Register</a>
                                <a href="admin_information.php">Information</a>
                                <a href="admin_promote.php">Promote</a>
                                <a href="admin_status.php">Status</a>
                                <a href="view_department.php">View Departments</a>
                            </div>
                        </div></li>
                        <li><a href="admin_dashboard.php" class="link active">Dashboard</a></li>
                        <li><a href="logout.php" class="link" onclick="return confirm('Are you sure you want to logout?');">Logout</a></li>
                    </ul>
                </div>
                <div class="nav-menu-btn">
                    <i class="bx bx-menu" onclick="myMenuFunction()"></i>
                </div>
            </nav>
        </div>
        <div class="main">
            <div class="page-header" style="margin-top: 2px;">
                <h1>Dashboard</h1>
                <small>Home / Dashboard</small>
            </div>
            <div class="report">
                        <div class="grid-container">
                            <main class="main-container" style="margin-top: 0px;">
                                <div class="charts">
                                    <div class="charts-card">
                                    <div class="graph-container">
                                        <canvas id="myChart"></canvas>
                                    </div>

                                    <?php
                                        $sql = "SELECT ce.date, ce.end_time, ce.agenda, uf.department
                                                FROM calendar_event ce
                                                INNER JOIN user_form uf ON ce.user_email = uf.user_email";

                                        $result = $conn->query($sql);

                                        $allDepartments = ['DIT', 'DIET', 'DAFE', 'DCEE', 'DCEA']; // Define all departments

                                        if ($result && $result->num_rows > 0) {
                                            $departments = array();

                                            while($row = $result->fetch_assoc()) {
                                                $date = date_create($row['date']);
                                                $month = date_format($date, 'F Y');
                                                $department = $row['department'];
                                                $end_time = $row['end_time'];
                                                $agenda = $row['agenda'];

                                                if (!isset($departments[$month])) {
                                                    $departments[$month] = array_fill_keys($allDepartments, array());
                                                }

                                                // Only count if the department is allowed
                                                if (in_array($department, $allDepartments)) {
                                                    // Modify the key based on date, end time, and agenda
                                                    $eventKey = $date->format('Y-m-d') . "-$end_time-$agenda";
                                                    $departments[$month][$department][$eventKey] = isset($departments[$month][$department][$eventKey]) ? $departments[$month][$department][$eventKey] + 1 : 1;
                                                }
                                            }
                                        } else {
                                            // Initialize chart data with zero counts for each department
                                            $departments = array();
                                            foreach ($allDepartments as $department) {
                                                $departments['No Events'] = array_fill_keys($allDepartments, 0);
                                            }
                                        }

                                        // Prepare data for Chart.js
                                        $chartData = array();
                                        foreach ($departments as $month => $departmentCounts) {
                                            $chartData[$month] = array();
                                            foreach ($departmentCounts as $department => $events) {
                                                // Check if $events is an array before calling count()
                                                $chartData[$month][$department] = is_array($events) ? count($events) : 0;
                                            }
                                        }
                                    ?>
                                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                                    <script>
                                        var ctx = document.getElementById('myChart').getContext('2d');

                                        var departmentColors = [
                                            '#FF6600',  // Color for DIT
                                            '#FF781F',   // Color for DIET
                                            '#FF8B3D',  // Color for DAFE
                                            '#FF9D5C',  // Color for DCEE
                                            '#FFAF7A'   // Color for DCEA
                                        ];

                                        var myChart = new Chart(ctx, {
                                            type: 'bar',
                                            data: {
                                                labels: <?php echo json_encode(array_keys($chartData)); ?>,
                                                datasets: [
                                                    <?php $index = 0; ?>
                                                    <?php foreach ($allDepartments as $department): ?>
                                                        {
                                                            label: '<?php echo $department; ?>',
                                                            backgroundColor: departmentColors[<?php echo $index; ?>],
                                                            borderColor: 'rgba(255, 255, 255, 1)',
                                                            borderWidth: 1,
                                                            data: [
                                                                <?php foreach ($chartData as $month => $counts): ?>
                                                                    <?php echo isset($counts[$department]) ? $counts[$department] : 0; ?>,
                                                                <?php endforeach; ?>
                                                            ],
                                                        },
                                                        <?php $index++; ?>
                                                    <?php endforeach; ?>
                                                ]
                                            },
                                            options: {
                                                scales: {
                                                    yAxes: [{
                                                        ticks: {
                                                            beginAtZero: true
                                                        }
                                                    }]
                                                }
                                            }
                                        });
                                    </script>
                                    </div>
                                </div>
                            </main>
                        </div>
                        <div class="charts card">
                            <form method="post">
                                <select name="month" id="month" style="background: #2a2a2a;border-color: transparent;border-radius: 5px;color: #f2f2f2;">
                                    <?php
                                    // Loop through months to generate options
                                    for ($i = 1; $i <= 12; $i++) {
                                        $selected = ($i == ($_POST["month"] ?? date("n"))) ? "selected" : ""; // Check if this month is selected
                                        echo "<option value='$i' $selected>" . date("F", mktime(0, 0, 0, $i, 1)) . "</option>"; // Output month name
                                    }
                                    ?>
                                </select>
                                <input type="submit" value="Select" style="background: #2a2a2a;border-color: transparent;border-radius: 5px;color: #f2f2f2;">
                            </form>
                            <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <caption>Monthly Event Reports</caption>
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Location</th>
                                        <th>PDF</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Connect to your database
                                    $servername = "localhost";
                                    $username = "root";
                                    $password = "";
                                    $dbname = "scheduler";

                                    $conn = new mysqli($servername, $username, $password, $dbname);

                                    if ($conn->connect_error) {
                                        die("Connection failed: " . $conn->connect_error);
                                    }

                                    // Get the current date and time in Philippines timezone
                                    $ph_timezone = new DateTimeZone('Asia/Manila');
                                    $ph_datetime = new DateTime('now', $ph_timezone);
                                    $current_date_time = $ph_datetime->format('Y-m-d H:i:s');

                                    // Check if form is submitted and get selected month
                                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                        $selected_month = $_POST["month"];
                                    } else {
                                        // Default to current month if no month is selected
                                        $selected_month = date('m');
                                    }

                                    // Delete records older than a week
                                    $delete_sql = "DELETE FROM calendar_page WHERE date < DATE_SUB(CURRENT_DATE(), INTERVAL 1 WEEK)";
                                    $conn->query($delete_sql);

                                    // Fetch data from the database for events in the selected month
                                    $sql = "SELECT DISTINCT date, start_time, end_time, location FROM calendar_event WHERE MONTH(date) = '$selected_month' AND date <= CURDATE() AND end_time <= '$current_date_time'";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        $count = 1;
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $count++ . "</td>";
                                            // Format date as Month-Day-Year
                                            echo "<td>" . date('M d, Y', strtotime($row['date'])) . "</td>";
                                            // Format time in 12-hour format
                                            echo "<td>" . date('h:i A', strtotime($row['start_time'])) . " - " . date('h:i A', strtotime($row['end_time'])) . "</td>";
                                            echo "<td>" . $row['location'] . "</td>";
                                            // Add view button with link to view the event details
                                            echo "<td><a href='view_event.php?date=" . $row['date'] . "&end_time=" . $row['end_time'] . "' style='text-decoration: none;background:#ee9f27;color:#f2f2f2;border-radius:10px;display:block;'>Download</a></td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='5'>No records found</td></tr>";
                                    }
                                    $conn->close();
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
        </div>
    </div>
    <footer class="footer"></footer>
    <script>
        function toggleDropdown() {
        var dropdownMenu = document.getElementById("dropdownMenu");
        if (dropdownMenu.style.display === "block") {
            dropdownMenu.style.display = "none";
        } else {
            dropdownMenu.style.display = "block";
        }
        }
    </script>
</body>
</html>