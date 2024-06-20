<?php
require 'database_connection.php';

// Start the session
session_start();

// Check if the user is logged in and the email address is set in the session
if (isset($_SESSION['user_email'])) {
    // Retrieve logged-in user's email address from session
    $logged_in_user_email = $_SESSION['user_email'];

    // Fetch the logged-in user's name from the user_form table
    $user_name_query = "SELECT name FROM user_form WHERE user_email = '$logged_in_user_email'";
    $user_name_result = mysqli_query($con, $user_name_query);
    $user_name_row = mysqli_fetch_assoc($user_name_result);
    $logged_in_user_name = $user_name_row['name'];

    // Fetch events with non-empty user_email on specific fields
    $display_query = "
        SELECT 
            ce.event_id, ce.date, ce.end_date, ce.start_time, ce.end_time, ce.location, ce.activity, ce.agenda, ce.author, ce.user_email, 
            uf.name AS user_name, uf_author.name AS author_name
        FROM calendar_event ce
        LEFT JOIN user_form uf ON ce.user_email = uf.user_email
        LEFT JOIN user_form uf_author ON ce.author = uf_author.user_email
        WHERE ce.user_email IS NOT NULL AND ce.user_email <> '' 
          AND ce.date IS NOT NULL AND ce.start_time IS NOT NULL AND ce.activity IS NOT NULL
    ";

    $results = mysqli_query($con, $display_query);

    if ($results) {
        $count = mysqli_num_rows($results);

        if ($count > 0) {
            $data_arr = array();
            while ($data_row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
                // Format event start and end dates
                $start_date = date("Y-m-d", strtotime($data_row['date']));
                $end_date = date("Y-m-d", strtotime($data_row['end_date']));

                $is_author = ($data_row['author'] === $logged_in_user_email);
                $is_user = ($logged_in_user_email === $data_row['user_email'] || $logged_in_user_email === $data_row['author']);

                // Determine event color based on the conditions
                if ($is_author) {
                    $color = '#FC6A03'; // Author's color
                } elseif ($is_user) {
                    $color = '#3CCF4E'; // User's color
                } else {
                    $color = '#003865'; 
                }

                // Key for identifying events with the same date, start time, activity, and agenda
                $key = $start_date . "_" . $data_row['start_time'] . "_" . $data_row['activity'] . "_" . $data_row['agenda'];

                // Check if event details with the same key already exist
                if (!isset($data_arr[$key])) {
                    $data_arr[$key] = array(
                        'event_id' => $data_row['event_id'],
                        'title' => $data_row['activity'],
                        'start' => $start_date,
                        'end' => $end_date,
                        'color' => $color,
                        'url' => '',
                        'start_time' => $data_row['start_time'],
                        'end_time' => $data_row['end_time'],
                        'location' => $data_row['location'],
                        'agenda' => $data_row['agenda'], // Include agenda in event details
                        'author' => $data_row['author_name'] ?? $data_row['author'],
                        'user_emails' => array()
                    );
                }

                // Add user email to the event's user_emails array
                $user_email = $data_row['user_name'] ?? $data_row['user_email'];
                if (!in_array($user_email, $data_arr[$key]['user_emails'])) {
                    $data_arr[$key]['user_emails'][] = $user_email;
                }
            }

            $data = array(
                'status' => true,
                'msg' => 'successfully!',
                'data' => array_values($data_arr), // Reset array keys to start from 0
                'logged_in_user_name' => $logged_in_user_name // Pass the logged-in user's name
            );
        } else {
            $data = array(
                'status' => false,
                'msg' => 'No events found!'
            );
        }
    } else {
        $data = array(
            'status' => false,
            'msg' => 'Database query error!'
        );
    }
} else {
    // Handle case where user is not logged in
    $data = array(
        'status' => false,
        'msg' => 'User is not logged in'
    );
}

// Output JSON response
echo json_encode($data);
?>