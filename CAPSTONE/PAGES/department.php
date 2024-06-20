<?php
session_start();

// Check if the user is logged in and has the appropriate user type
if (!isset($_SESSION['user_email']) || ($_SESSION['user_type'] !== 'Admin' && $_SESSION['user_type'] !== 'Implementor')) {
    // Redirect to unauthorized page or login page
    header("Location: unauthorized.php");
    exit();
}

$department_id = $_GET['id'];

// Include the database configuration file
include 'config.php';

// Fetch department name
$sql = "SELECT name FROM departments WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $department_id);
$stmt->execute();
$department_result = $stmt->get_result();
$department = $department_result->fetch_assoc();

// Fetch members from user_form table
$sql = "SELECT u.id, u.name 
        FROM user_form u 
        JOIN departments d ON u.department = d.name 
        WHERE d.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $department_id);
$stmt->execute();
$members_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="icon" type="image/x-icon" href="img/logo.png">
    <link rel="stylesheet" href="css/font_1.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="practices/style.css">
    <title><?php echo $department['name']; ?> Department</title>
    <link href="javascript/bootstrap.min.css" rel="stylesheet">
</head>
<body>
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
        <h1 class="mt-4"><?php echo $department['name']; ?> Department</h1>
        <a href="view_department.php" class="btn btn-link mb-3" style="color: #FC6A03;">Back to Departments</a>
        <h2>Members</h2>
        <ul class="list-group">
            <?php
            if ($members_result->num_rows > 0) {
                while($row = $members_result->fetch_assoc()) {
                    echo '<li class="list-group-item">' . $row["name"] . '</li>';
                }
            } else {
                echo '<li class="list-group-item">No members found.</li>';
            }
            ?>
        </ul>
        <?php 
        // Check if the user is an Admin
        if ($_SESSION['user_type'] === 'Admin') { 
        ?>
        <div>
            <!-- Update Department Button -->
            <a href="update_department.php?id=<?php echo $department_id; ?>" class="btn btn-primary">Update Department</a>
            <!-- Delete Department Button -->
            <a href="delete_department.php?id=<?php echo $department_id; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this department and all associated members?')">Delete Department</a>
        </div>
        <?php } ?>
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
    <script src="javascript/jquery-3.5.1.slim.min.js"></script>
    <script src="javascript/popper.min.js"></script>
    <script src="javascript/bootstrap.min.js"></script>
</body>
</html>
