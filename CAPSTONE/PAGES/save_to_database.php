<?php
// Include your database connection code or config file if not already included
@include 'config.php';

// Establish database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "scheduler";

$conn = mysqli_connect($host, $username, $password, $database);

// Check if the connection is successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $user_email = mysqli_real_escape_string($conn, $_POST['user_email']);
    $password = $_POST['password'];
    $position_type = mysqli_real_escape_string($conn, $_POST['position_type']);
    $user_type = $_POST['user_type'];
    $department = $_POST['department'];
    
    // Determine the position based on the value set by selectPosition()
    $position = ($_POST['position'] == 'Faculty Member') ? 'Faculty Member' : 'Officials';

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and execute the SQL query to insert data into your database table
    $sql = "INSERT INTO user_form (name, user_email, password, position, position_type, user_type, department) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssssss", $name, $user_email, $hashed_password, $position, $position_type, $user_type, $department);

        if (mysqli_stmt_execute($stmt)) {
            echo 'success';
        } else {
            echo 'failure: ' . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo 'failure: ' . mysqli_error($conn);
    }
} else {
    echo "Invalid request";
}

// Close the database connection
mysqli_close($conn);
?>
