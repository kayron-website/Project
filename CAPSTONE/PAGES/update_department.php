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
    $new_department_name = $_POST['new_department_name'];
    $new_department_full_name = $_POST['full'];

    // Update department name and full name in departments table
    $update_department_sql = "UPDATE departments SET name = ?, full = ? WHERE id = ?";
    $stmt = $conn->prepare($update_department_sql);
    $stmt->bind_param("ssi", $new_department_name, $new_department_full_name, $department_id);
    $stmt->execute();

    // Update department name in user_form table
    $update_member_department_sql = "UPDATE user_form SET department = ? WHERE department = (SELECT name FROM departments WHERE id = ?)";
    $stmt = $conn->prepare($update_member_department_sql);
    $stmt->bind_param("si", $new_department_name, $department_id);
    $stmt->execute();

    // Redirect back to the department page
    header("Location: department.php?id=$department_id");
    exit();
}

// Fetch department name and full name
$sql = "SELECT name, full FROM departments WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $department_id);
$stmt->execute();
$department_result = $stmt->get_result();
$department = $department_result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Department</title>
    <link href="javascript/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-4">Update <?php echo $department['name']; ?> Department</h1>
        <form method="post">
            <div class="form-group">
                <label for="new_department_name">New Department Name</label>
                <input type="text" class="form-control" id="new_department_name" name="new_department_name" value="<?php echo $department['name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="full">Department Full Name:</label>
                <input type="text" class="form-control" id="full" name="full" value="<?php echo $department['full']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="department.php?id=<?php echo $department_id; ?>" class="btn btn-secondary">Back</a>
        </form>
    </div>
    <script src="javascript/jquery-3.5.1.slim.min.js"></script>
    <script src="javascript/popper.min.js"></script>
    <script src="javascript/bootstrap.min.js"></script>
</body>
</html>
