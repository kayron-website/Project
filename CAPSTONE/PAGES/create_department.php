<?php
// create_department.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $department_name = $_POST['department_name'];
    $full = $_POST['full'];

    // Include the database configuration file
    include 'config.php';

    // Check if the department already exists
    $check_sql = "SELECT * FROM departments WHERE name = ? OR full = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ss", $department_name, $full);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Department already exists";
    } else {
        // Insert department
        $insert_sql = "INSERT INTO departments (name, full) VALUES (?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("ss", $department_name, $full);
        if ($stmt->execute() === TRUE) {
            echo "New department created successfully";
        } else {
            echo "Error: " . $insert_sql . "<br>" . $conn->error;
        }
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Department</title>
    <link href="javascript/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-4">Create Department</h1>
        <form method="post" action="">
            <div class="form-group">
                <label for="department_name">Department Abbreviation Name:</label>
                <input type="text" class="form-control" id="department_name" name="department_name" required>
            </div>
            <div class="form-group">
                <label for="full">Department Full Name:</label>
                <input type="text" class="form-control" id="full" name="full" required>
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
        </form>
        <a href="view_department.php" class="btn btn-link mt-3" style="color: #FC6A03;">View Departments</a>
    </div>
    <script src="javascript/jquery-3.5.1.slim.min.js"></script>
    <script src="javascript/popper.min.js"></script>
    <script src="javascript/bootstrap.min.js"></script>
</body>
</html>
