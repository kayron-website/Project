<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "scheduler";

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

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch distinct departments
$sql = "SELECT DISTINCT department FROM user_form";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Departments</title>
    <link href="javascript/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="img/logo.png">
    <link rel="stylesheet" href="css/font_1.css">
	<link rel="stylesheet" href="css/button.css">
	<script src="javascript/fetch_department_members.js"></script>
    <link rel="stylesheet" href="practices/style.css">
    <link href='css/boxicons.min.css' rel='stylesheet'>
    <style>
        .fc-view-container {
            margin-top: 10px;
        }
        .fc-toolbar fc-header-toolbar {
            background: orange;
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
                    <li><a href="admin_calendar.php" class="link active">Calendar</a></li>
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
                    <li><a href="admin_dashboard.php" class="link">Dashboard</a></li>
                    <li><a href="logout.php" class="link" onclick="return confirm('Are you sure you want to logout?');">Logout</a></li>
                </ul>
            </div>
            <div class="nav-menu-btn">
                <i class="bx bx-menu" onclick="myMenuFunction()"></i>
            </div>
        </nav>
    <div class="container mt-5">
        <h1>Edit Departments</h1>
        <a href="view_department.php" class="btn btn-link mb-3" style="color: #FC6A03;">Back to Departments</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Departments</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $row['department'] . "</td>
                                <td>
                                    <button class='btn btn-primary edit-btn' data-department='" . $row['department'] . "'>Edit</button>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>No departments found</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

    <!-- Edit Department Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="update_department_name.php" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Department</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="old_department" id="oldDepartment">
                        <div class="mb-3">
                            <label for="newDepartment" class="form-label">New Department Name</label>
                            <input type="text" class="form-control" name="new_department" id="newDepartment" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var editButtons = document.querySelectorAll('.edit-btn');
            editButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var department = button.getAttribute('data-department');
                    document.getElementById('oldDepartment').value = department;
                    var editModal = new bootstrap.Modal(document.getElementById('editModal'));
                    editModal.show();
                });
            });
        });
    </script>
</body>
</html>
