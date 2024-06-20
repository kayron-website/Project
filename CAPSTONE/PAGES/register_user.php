<?php
@include 'config.php';

if (isset($_POST['register'])) {
    // Rest of your registration logic remains the same as in your original file
    // ...

    // After performing the necessary checks and actions:
    if ($insert_result) {
        // Return a success response to the AJAX call
        echo json_encode(array("success" => true));
        exit(); // Stop further execution
    } else {
        $register_error = 'Failed to register. Please try again.';
        // Return an error response to the AJAX call
        echo json_encode(array("success" => false, "error" => $register_error));
        exit(); // Stop further execution
    }
}
?>
