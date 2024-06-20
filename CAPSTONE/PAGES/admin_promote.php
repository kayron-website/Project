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
        .container {
    display: grid;
    grid-template-columns: repeat(1, 1fr);
    justify-content: center;
    align-items: center;
    height: 100%;
    background-color: #f2f2f2;
    margin-top: 10px;
}

#customers {
    border-collapse: collapse;
    width: 100%;
    color: #f2f2f2;
}

#customers th, #customers td {
    border: 1px solid #555; /* Border color */
    padding: 8px;
}

#customers th {
    background-color: #333; /* Header background color */
}

#customers tr:nth-child(even) {
    background-color: #f2f2f2; /* Even row background color */
    color:#f2f2f2;
}

#customers tr:nth-child(odd) {
    background-color: #ffddba; /* Odd row background color */
    color:#f2f2f2;
}

#customers th, #customers td {
    text-align: center;
}

.edit-form {
    display: none;
}

.status {
    background-color: #4CAF50; /* Green */
    border: none;
    color: white;
    padding: 5px 10px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 14px;
    margin: 4px 2px;
    cursor: pointer;
    border-radius: 4px;
}

.filter{
    margin-bottom: 5px;
    margin-left: 10px;
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
                            <a href="#" class="link active" onclick="toggleDropdown()">Settings</a>
                            <div id="dropdownMenu" class="dropdown-content">
                                <a href="admin_register.php">Register</a>
                                <a href="admin_information.php">Information</a>
                                <a href="admin_promote.php">Promote</a>
                                <a href="admin_status.php">Status</a>
                                <a href="view_department.php">View Departments</a>
                            </div>
                        </div></li>
                        <li><a href="admin_dashboard.php" class="link">Dashboard</a></li>
                        <li><a href="logout.php" class="link" onclick="return confirm('Are you sure you want to logout?');">Logout</a></li>
                    </ul>
                </div>
                <div class="nav-menu-btn">
                    <i class="bx bx-menu" onclick="myMenuFunction()"></i>
                </div>
            </nav>
        </div>
        <div class="container">
            <div class="filter">
                <label for="department-filter">Filter by Department:</label>
                <select id="department-filter" onchange="filterByDepartment(this.value)" style="border-color: transparent;color: #ffffff;background: #FC6A03;border-radius: 5px;">
                    <option value="All">All</option>
                    <option value="DIT">DIT</option>
                    <option value="DIET">DIET</option>
                    <option value="DCEE">DCEE</option>
                    <option value="DCEA">DCEA</option>
                    <option value="DAFE">DAFE</option>
                </select>
            </div>
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

            $sql = "SELECT user_email, name, user_type, department FROM user_form WHERE user_type = 'Attendee'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<table id='customers'>";
                echo "<tr><th>User Email</th><th>User Type</th><th>Department</th><th></th></tr>";

                while ($row = $result->fetch_assoc()) {
                    echo "<tr class='data-row' data-department='" . $row["department"] . "'>";
                    echo "<td>" . $row["name"] . "</td>";
                    echo "<td>" . $row["user_type"] . "</td>";
                    echo "<td>" . $row["department"] . "</td>";

                    echo "<td><form class='promoteForm' method='post' action='promote_user.php' onsubmit='return confirmPromotion()'>";
                    echo "<input type='hidden' name='user_email' value='" . $row["user_email"] . "'>";
                    echo "<input type='submit' value='Update' style='background-color: #FC6A03;
                    border: none;
                    color: white;
                    padding: 5px 15px;
                    text-align: center;
                    text-decoration: none;
                    display: inline-block;
                    font-size: 16px;
                    margin: 4px 2px;
                    cursor: pointer;
                    border-radius: 10px;'></form></td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "No users found.";
            }
            $conn->close();
        ?>
        </div>
    </div>
    <script>
        function toggleDropdown() {
        var dropdownMenu = document.getElementById("dropdownMenu");
        if (dropdownMenu.style.display === "block") {
            dropdownMenu.style.display = "none";
        } else {
            dropdownMenu.style.display = "block";
        }
        }
        function confirmPromotion() {
            return confirm("Are you sure you want to promote this user?");
        }
        function filterByDepartment(department) {
            var rows = document.querySelectorAll('tr.data-row');
            rows.forEach(function(row) {
                if (department === 'All' || row.getAttribute('data-department') === department) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>
