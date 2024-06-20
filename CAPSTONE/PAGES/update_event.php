<?php
// Include database connection
include 'database_connection.php';

// Function to update records by event ID
function updateRecordsByEventId($conn, $eventId, $newDate, $newStartTime, $newEndTime, $newActivity, $newLocation, $newAgenda) {
    // Start by updating the calendar_event table
    $query1 = "UPDATE calendar_event 
               SET date = IF(?, ?, date), start_time = ?, end_time = ?, activity = ?, location = ?, agenda = ?
               WHERE event_id = ?";
    
    $statement1 = $conn->prepare($query1);
    
    if (!$statement1) {
        throw new Exception('Prepare failed: ' . $conn->error);
    }
    
    // Bind parameters to the prepared statement
    $statement1->bind_param("ssssssss", $newDate, $newDate, $newStartTime, $newEndTime, $newActivity, $newLocation, $newAgenda, $eventId);
    
    // Execute the prepared statement
    if (!$statement1->execute()) {
        throw new Exception('Execute failed: ' . $statement1->error);
    }
    
    // Close the statement
    $statement1->close();
    
    // Now update the table_sched table
    $query2 = "UPDATE table_sched 
               SET date = IF(?, ?, date), start_time = ?, end_time = ?, activity = ?, location = ?, agenda = ?
               WHERE event_id = ?";
    
    $statement2 = $conn->prepare($query2);
    
    if (!$statement2) {
        throw new Exception('Prepare failed: ' . $conn->error);
    }
    
    // Bind parameters to the prepared statement
    $statement2->bind_param("ssssssss", $newDate, $newDate, $newStartTime, $newEndTime, $newActivity, $newLocation, $newAgenda, $eventId);
    
    // Execute the prepared statement
    if (!$statement2->execute()) {
        throw new Exception('Execute failed: ' . $statement2->error);
    }
    
    // Close the statement
    $statement2->close();
    
    return true; // Assuming both updates were successful
}

try {
    // Check if all required POST parameters are set
    if (isset($_POST['event_id'], $_POST['new_date'], $_POST['new_start_time'], $_POST['new_end_time'], $_POST['new_activity'], $_POST['new_location'], $_POST['new_agenda'])) {
        
        // Retrieve POST data
        $eventId = $_POST['event_id'];
        $newDate = $_POST['new_date'];
        $newStartTime = $_POST['new_start_time'];
        $newEndTime = $_POST['new_end_time'];
        $newActivity = $_POST['new_activity'];
        $newLocation = $_POST['new_location'];
        $newAgenda = $_POST['new_agenda'];
        
        // Update records in the database
        $success = updateRecordsByEventId($con, $eventId, $newDate, $newStartTime, $newEndTime, $newActivity, $newLocation, $newAgenda);
        
        // Construct JSON response
        if ($success) {
            $response = array('success' => true, 'message' => 'Event updated successfully.');
        } else {
            $response = array('success' => false, 'message' => 'Failed to update event.');
        }
    } else {
        $response = array('success' => false, 'message' => 'Missing required parameters.');
    }
} catch (Exception $e) {
    // Handle exceptions
    $response = array('success' => false, 'message' => $e->getMessage());
}

// Output JSON response
echo json_encode($response);
?>
