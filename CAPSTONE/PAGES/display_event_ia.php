<?php
require 'database_connection.php';

// Start the session
session_start();

// Check if the user is logged in and the email address is set in the session
if (isset($_SESSION['user_email'])) {
    // Retrieve logged-in user's email address from session
    $logged_in_user_email = $_SESSION['user_email'];

    // Fetch the activity, start time, and end time of the logged-in user's events from the database
    $get_user_schedule_query = "SELECT activity, start_time, end_time FROM calendar_event WHERE user_email = ? LIMIT 1";
    $stmt_schedule = mysqli_prepare($con, $get_user_schedule_query);
    mysqli_stmt_bind_param($stmt_schedule, "s", $logged_in_user_email);
    mysqli_stmt_execute($stmt_schedule);
    $user_schedule_result = mysqli_stmt_get_result($stmt_schedule);
    $user_schedule_row = mysqli_fetch_assoc($user_schedule_result);

    if ($user_schedule_row) {
        $activity = $user_schedule_row['activity'];
        $start_time = $user_schedule_row['start_time'];
        $end_time = $user_schedule_row['end_time'];

        // Fetch events for the logged-in user and other users with the same schedules
        $display_query = "SELECT ce.event_id, ce.date, ce.end_date, ce.start_time, ce.end_time, ce.location, ce.activity, ce.agenda, ce.author, ce.user_email, uf.name AS user_name, uf_author.name AS author_name
                          FROM calendar_event ce
                          LEFT JOIN user_form uf ON ce.user_email = uf.user_email
                          LEFT JOIN user_form uf_author ON ce.author = uf_author.user_email
                          WHERE ((ce.user_email = ? OR ce.author = ?) OR (ce.activity = ? AND ce.start_time = ? AND ce.end_time = ? AND ce.agenda = ?)) AND ce.date IS NOT NULL AND ce.start_time IS NOT NULL AND ce.activity IS NOT NULL AND ce.agenda IS NOT NULL";

        // Using prepared statement to prevent SQL injection
        $stmt = mysqli_prepare($con, $display_query);
        mysqli_stmt_bind_param($stmt, "ssssss", $logged_in_user_email, $logged_in_user_email, $activity, $start_time, $end_time, $agenda);

        mysqli_stmt_execute($stmt);
        $results = mysqli_stmt_get_result($stmt);

        if ($results) {
            $data_arr = array();
            while ($data_row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
                // Format event start and end dates
                $start_date = date("Y-m-d", strtotime($data_row['date']));
                $end_date = date("Y-m-d", strtotime($data_row['end_date']));

                // Define default color
                $color = ($data_row['author'] === $logged_in_user_email) ? '#FC6A03' : '#5f8f14';

                // Key for identifying events with same date, start time, activity, and agenda
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
                        'author' => ($data_row['author_name']) ? $data_row['author_name'] : $data_row['author'],
                        'user_emails' => array()
                    );
                }

                // Add user email to the event's user_emails array
                $user_email = ($data_row['user_name']) ? $data_row['user_name'] : $data_row['user_email'];

                // Check if the user_email is the logged-in user's email
                $is_logged_in_user = ($user_email === $logged_in_user_email);

                // Add the user_email to the beginning of the user_emails array if it's the logged-in user
                if ($is_logged_in_user) {
                    // Check if the user_email is already in the array, if so, remove it
                    $index = array_search($user_email, $data_arr[$key]['user_emails']);
                    if ($index !== false) {
                        unset($data_arr[$key]['user_emails'][$index]);
                    }
                    // Add the user_email to the beginning
                    array_unshift($data_arr[$key]['user_emails'], $user_email);
                } else {
                    // Add the user_email to the array if it's not the logged-in user
                    $data_arr[$key]['user_emails'][] = $user_email;
                }
            }

            $data = array(
                'status' => true,
                'msg' => 'Successfully fetched events',
                'data' => array_values($data_arr) // Reset array keys to start from 0
            );
        } else {
            $data = array(
                'status' => false,
                'msg' => 'No events found for this user'
            );
        }
    } else {
        // Handle case where no schedule is found for the logged-in user
        $data = array(
            'status' => false,
            'msg' => 'No schedule found for the logged-in user'
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
