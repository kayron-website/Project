<?php
$conn = new mysqli("localhost", "root", "", "scheduler");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_email = $_POST["user_email"];

    $update_sql = "UPDATE user_form SET user_type = 'Implementor' WHERE user_email = '$user_email'";
    if ($conn->query($update_sql) === TRUE) {
        header("Location: admin_promote.php");
        exit();
    } else {
        echo "Error promoting user: " . $conn->error;
    }
} else {
    echo "Invalid request";
}
?>