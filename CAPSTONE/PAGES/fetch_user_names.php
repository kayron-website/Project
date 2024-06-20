<?php
// Assuming you have a database connection established
// Replace with your actual database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "scheduler";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to fetch user names based on emails
function fetchUserNames($conn, $user_emails) {
    // Escape and sanitize emails to prevent SQL injection
    $escaped_emails = array_map(function($email) use ($conn) {
        return $conn->real_escape_string($email);
    }, $user_emails);

    // Prepare SQL query with placeholders for emails
    $sql = "SELECT user_email, name FROM user_form WHERE user_email IN ('" . implode("', '", $escaped_emails) . "')";

    // Execute query
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $names = array();
        // Fetch names and store them in an associative array
        while ($row = $result->fetch_assoc()) {
            $names[$row['user_email']] = $row['name'];
        }
        return $names;
    } else {
        return array(); // Return empty array if no names found
    }
}

// Check if POST request contains 'user_emails' parameter
if (isset($_POST['user_emails'])) {
    $user_emails = $_POST['user_emails'];

    // Call function to fetch names based on provided user_emails
    $names = fetchUserNames($conn, $user_emails);

    // Return names as JSON response
    header('Content-Type: application/json');
    echo json_encode(array('names' => $names));
} else {
    // Handle case where 'user_emails' parameter is not provided
    echo "No user emails provided.";
}

// Close database connection
$conn->close();
?>
