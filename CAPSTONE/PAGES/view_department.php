<?php
session_start();

// Include the database configuration file
include 'config.php';

// Fetch departments
$sql = "SELECT id, name, full FROM departments";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Departments</title>
    <link rel="icon" type="image/x-icon" href="img/logo.png">
    <link rel="stylesheet" href="css/font_1.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="practices/style.css">
    <link rel="stylesheet" href="css/members.css">
    <link href="javascript/bootstrap.min.css" rel="stylesheet">
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
        <div class="main">
            <?php 
            // Display the "Create New Department" button only if the user is logged in and has the appropriate user type
            if (isset($_SESSION['user_email']) && $_SESSION['user_type'] === 'Admin') { 
            ?>
           
            <a href="create_department.php" class="asd" style="border-radius: 10px;position: absolute;background: #2a2a2a;color: #f2f2f2;padding: 8px;margin-left: 10px;margin-top: -30px;">Create New Department</a>
            <a href="edit_admin_departments.php" class="asd" style="border-radius: 10px;position: absolute;background: #2a2a2a;color: #f2f2f2;padding: 8px;margin-left: 250px;margin-top: -30px;">Edit Members Department</a>
           
            <?php } ?>
           
            <div class="row">
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo '<div class="cardBox_3">';
                        echo '<div class="card">';
                        echo '<div class="content-box"><span class="card-title">' . $row["full"] . '</span></div>';
                        echo '<a href="department.php?id=' . $row["id"] . '" class="asd"><button class="btn">View Members<div class="mem"></div></button></a>';
                        echo '<div class="date-box"><span class="date">' . $row["name"] . '</span></</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo "No departments found.";
                }
                $conn->close();
                ?>
            </div>
        </div>
    </div>
    <script src="javascript/jquery-3.5.1.slim.min.js"></script>
    <script src="javascript/popper.min.js"></script>
    <script src="javascript/bootstrap.min.js"></script>
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
