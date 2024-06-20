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
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100%;
    background-color: #f2f2f2;
    margin-top: 10px;
}

#customers {
    border-collapse: collapse;
    width: 100%;
    color: #f2f2f2; /* Text color */
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
    background-color: #FC6A03; /* Green */
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

#table-container{
    width: 100%;
}
    </style>
</head>
<body>
    <script>
        function changeStatus(email, newStatus) {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
            // Update the button text or perform any other action if needed
            console.log(xhr.responseText);
            }
        };
        xhr.open("GET", "update_status.php?email=" + email + "&status=" + newStatus, true);
        xhr.send();
        }
    </script>
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
            <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "scheduler";

            $conn = new mysqli($servername, $username, $password, $dbname);

            $sql = "SELECT user_email, name, user_type, department, status FROM user_form";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<div id='table-container'>";
                echo "<div style='margin-top: 5px;display: flex;margin-left: 10px;'>";
                echo "<h5>Search Name</h5>";
                echo "<input type='text' id='search' placeholder='Search Name' style='color: #2a2a2a;margin-bottom: 5px;margin-left: 10px;border-radius: 5px;'>";
                echo "</div>";
                echo "<table id='customers'>";
                echo "<tr><th>User Email</th><th>User Type</th><th>Department</th><th>Status</th></tr>";

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["name"] . "</td>";
                    echo "<td>" . $row["user_type"] . "</td>";
                    echo "<td>" . $row["department"] . "</td>";
                    // Show Active or Inactive button based on the status
                    echo "<td>";
                    if ($row["status"] == 0) {
                        echo "<button class='status' onclick='changeStatus(\"" . $row["user_email"] . "\", \"Active\", this)'>Active</button>";
                    } else {
                        echo "<button class='status' onclick='changeStatus(\"" . $row["user_email"] . "\", \"Inactive\", this)'>Inactive</button>";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table>";
                echo "</div>";
            } else {
                echo "0 results";
            }
            $conn->close();
            ?>

            <script>
            document.getElementById('search').addEventListener('input', function() {
                var filter, table, tr, td, i, txtValue;
                filter = this.value.toUpperCase();
                table = document.getElementById('customers');
                tr = table.getElementsByTagName('tr');
                for (i = 0; i < tr.length; i++) {
                    td = tr[i].getElementsByTagName('td')[0];
                    if (td) {
                        txtValue = td.textContent || td.innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = '';
                        } else {
                            tr[i].style.display = 'none';
                        }
                    }
                }
            });
            </script>
                </div>
                <script>
                    function changeStatus(userEmail, newStatus, button) {
                        var xhr = new XMLHttpRequest();
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState === XMLHttpRequest.DONE) {
                                if (xhr.status === 200) {
                                    // On success, update the button text and status attribute
                                    if (newStatus === "Active") {
                                        button.innerHTML = "Inactive";
                                    } else {
                                        button.innerHTML = "Active";
                                    }
                                    button.setAttribute("onclick", "changeStatus('" + userEmail + "', '" + (newStatus === "Active" ? "Inactive" : "Active") + "', this)");
                                } else {
                                    // Handle errors here if needed
                                    console.error('Request failed: ' + xhr.status);
                                }
                            }
                        };
                        xhr.open('GET', 'update_status.php?email=' + userEmail + '&status=' + newStatus, true);
                        xhr.send();
                    }
                </script>
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
    </script>
</body>
</html>