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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $oldDepartment = $_POST['old_department'];
    $newDepartment = $_POST['new_department'];

    // Update department name in the database
    $stmt = $conn->prepare("UPDATE user_form SET department = ? WHERE department = ?");
    $stmt->bind_param("ss", $newDepartment, $oldDepartment);

    if ($stmt->execute()) {
        echo "Department updated successfully";
    } else {
        echo "Error updating department: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    // Redirect back to the edit departments page
    header('Location: edit_admin_departments.php');
    exit;
}
?>
