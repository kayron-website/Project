<?php
// Check if event_id and checkedEmails are provided via POST
if (isset($_POST['date']) && isset($_POST['checkedEmails'])) {
    // Sanitize input
    $event_id = $_POST['date'];
    $checkedEmails = $_POST['checkedEmails'];

    try {
        // Database connection details
        $host = 'localhost';
        $dbname = 'scheduler';
        $username = 'root';
        $password = '';

        // Establish a new PDO connection
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        
        // Set PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Prepare a SQL query to delete calendar events associated with the checked emails
        $placeholders = rtrim(str_repeat('?,', count($checkedEmails)), ',');
        $sql = "DELETE FROM calendar_event WHERE date = ? AND user_email IN ($placeholders)";
        
        // Prepare and execute the statement
        $stmt = $conn->prepare($sql);
        $params = array_merge([$event_id], $checkedEmails);
        $stmt->execute($params);
        
        // Check if deletion was successful
        $deletedRows = $stmt->rowCount();
        if ($deletedRows > 0) {
            // Return success response
            $response = array("status" => true, "message" => "$deletedRows calendar event(s) deleted successfully.");
            echo json_encode($response);
        } else {
            // Return error response if no rows were affected
            $response = array("status" => false, "message" => "No calendar events deleted.");
            echo json_encode($response);
        }
    } catch (PDOException $e) {
        // Return error response if database error occurs
        $response = array("status" => false, "message" => "Database error: " . $e->getMessage());
        echo json_encode($response);
    } finally {
        // Close the database connection
        $conn = null;
    }
} else {
    // Return error response if event_id or checkedEmails are missing
    $response = array("status" => false, "message" => "Missing parameters.");
    echo json_encode($response);
}
?>
