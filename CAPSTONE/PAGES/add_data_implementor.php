<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "scheduler";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Extract data from the form
    $start_time = $_POST["start_time"];
	$end_time = $_POST["end_time"];
    $date = $_POST["date"];
    $subject = $_POST["subject"];
    $room = $_POST["room"];
    
    // Retrieve the user's email from the session
    session_start();
    if (isset($_SESSION['user_email'])) {
        $user_email = $_SESSION['user_email'];

        // SQL query to insert data into the database, including the user's email
        $sql = "INSERT INTO class_sched (start_time, end_time, date, subject, room, user_email) VALUES ('$start_time', '$end_time', '$date', '$subject', '$room', '$user_email')";

        if ($conn->query($sql) === TRUE) {
            header('Location: profile_implementor.php');
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        // Handle the case where the user's email is not in the session
        echo "User email not found in the session.";
    }
}

$conn->close();
?>
