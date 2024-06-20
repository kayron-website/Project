<?php
session_start();

// Check if the user is logged in and has the appropriate user type
if (!isset($_SESSION['user_email']) || ($_SESSION['user_type'] !== 'Admin')) {
    // Redirect to unauthorized page or login page
    header("Location: unauthorized.php");
    exit();
}

$department_id = $_GET['id'];

// Include the database configuration file
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Delete department from departments table
    $delete_department_sql = "DELETE FROM departments WHERE id = ?";
    $stmt = $conn->prepare($delete_department_sql);
    $stmt->bind_param("i", $department_id);
    $stmt->execute();

    // Delete associated members from user_form table
    $delete_members_sql = "DELETE FROM user_form WHERE department = (SELECT name FROM departments WHERE id = ?)";
    $stmt = $conn->prepare($delete_members_sql);
    $stmt->bind_param("i", $department_id);
    $stmt->execute();

    // Redirect back to the departments view page
    header("Location: view_department.php");
    exit();
}

// Fetch department name
$sql = "SELECT name FROM departments WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $department_id);
$stmt->execute();
$department_result = $stmt->get_result();
$department = $department_result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Department</title>
    <link href="javascript/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-4">Delete <?php echo $department['name']; ?> Department</h1>
        <p>Are you sure you want to delete this department and all associated members?</p>
        <form method="post">
            <button type="submit" class="btn btn-danger">Delete</button>
            <a href="department.php?id=<?php echo $department_id; ?>" class="btn btn-secondary">Back</a>
        </form>
    </div>
    <script src="javascript/jquery-3.5.1.slim.min.js"></script>
    <script src="javascript/popper.min.js"></script>
    <script src="javascript/bootstrap.min.js"></script>
</body>
</html>
