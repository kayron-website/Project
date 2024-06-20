<?php
// Start the session to access session variables
session_start();

// Replace these variables with your actual database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "scheduler";

try {
    // Establish a connection to the database
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set PDO to throw exceptions on error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the user is logged in and get their email from the session
    if (isset($_SESSION['user_email'])) {
        $loggedInUserEmail = $_SESSION['user_email'];

        // Fetch events for the logged-in user from the database
        $stmt = $pdo->prepare("SELECT activity, date, start_time, end_time FROM table_sched WHERE user_email = :user_email");
        $stmt->bindParam(':user_email', $loggedInUserEmail);
        $stmt->execute();
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Format events for FullCalendar
        $formattedEvents = array();
        foreach ($events as $event) {
            // Create a new event object
            $formattedEvent = array(
                'title' => $event['activity'],
                'start' => $event['date'] . 'T' . $event['start_time'],
                'end' => $event['date'] . 'T' . $event['end_time']
            );

            // Push the event to the formatted events array
            $formattedEvents[] = $formattedEvent;
        }

        // Output the events in JSON format (to be used in your JavaScript)
        echo json_encode($formattedEvents);
    } else {
        echo "User not logged in."; // Handle if user is not logged in
    }
} catch(PDOException $e) {
    // Handle database connection error
    echo "Connection failed: " . $e->getMessage();
}
?>
