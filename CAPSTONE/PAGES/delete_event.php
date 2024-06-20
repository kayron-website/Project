<?php
// Include your database connection file
require 'database_connection.php';

// Check if the event_id is set and not empty
if (isset($_POST['event_id']) && !empty($_POST['event_id'])) {
    // Retrieve the event ID from the POST data
    $eventId = $_POST['event_id'];

    // Prepare a delete query
    $delete_query = "DELETE FROM calendar_event WHERE event_id = ?";

    // Using prepared statement to prevent SQL injection
    $stmt = mysqli_prepare($con, $delete_query);
    mysqli_stmt_bind_param($stmt, "i", $eventId);
    
    // Execute the delete query
    if (mysqli_stmt_execute($stmt)) {
        // Event deleted successfully
        $response = array(
            'status' => true,
            'msg' => 'Event deleted successfully'
        );
    } else {
        // Error occurred while deleting event
        $response = array(
            'status' => false,
            'msg' => 'Error deleting event'
        );
    }

    // Close the prepared statement
    mysqli_stmt_close($stmt);
} else {
    // Event ID not provided or empty
    $response = array(
        'status' => false,
        'msg' => 'Event ID not provided'
    );
}

// Output JSON response
echo json_encode($response);
?>
