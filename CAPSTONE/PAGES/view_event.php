<?php
require_once('tcpdf/tcpdf.php'); // Include TCPDF library

// Get the date and end_time from the form data
$date = $_GET['date'];
$end_time = $_GET['end_time'];

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "scheduler";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch event details from the calendar_page table based on date and end_time
$sql = "SELECT * FROM calendar_page WHERE date = '$date' AND end_time = '$end_time'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Fetch event details from the calendar_event table based on date, end_time, and activity
        $activity = $row['activity'];
        $event_sql = "SELECT * FROM calendar_event WHERE date = '$date' AND end_time = '$end_time' AND activity = '$activity'";
        $event_result = $conn->query($event_sql);

        if ($event_result->num_rows > 0) {
            while ($event_row = $event_result->fetch_assoc()) {
                // Retrieve the author information
                $author = $event_row['author'];
                
                // Fetch author's position from user_form table
                $position_sql = "SELECT position FROM user_form WHERE name = '$author'";
                $position_result = $conn->query($position_sql);
                $author_position = ($position_result->num_rows > 0) ? $position_result->fetch_assoc()['position'] : '';

                // Create a new PDF instance
                $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

                $pdf->setPrintHeader(false);

                // Set document information
                $pdf->SetCreator('Event Scheduler');
                $pdf->SetAuthor('Capstone');
                $pdf->SetTitle('Event Details');

                // Add a page
                $pdf->AddPage();

                // Add content to the PDF
                $pdf->Image('icon/cvsu.jpg', 38, 10, 30, '', 'JPG', '', 'T', false, 100, '', false, false, 0, false, false, false);
                $pdf->Ln(1);

                $pdf->SetFont('helvetica', 'B', 10);
                $pdf->Write(0, 'Republic of the Philippines', '', 0, 'C', true);
                $pdf->Ln(1);

                // Cavite State University
                $pdf->SetFont('helvetica', 'B', 14);
                $pdf->Write(0, 'Cavite State University', '', 0, 'C', true);
                $pdf->Ln(1);

                // Don Severino de las Alas Campus
                $pdf->SetFont('helvetica', 'B', 12);
                $pdf->Write(0, 'Don Severino de las Alas Campus', '', 0, 'C', true);
                $pdf->Ln(1);

                // Indang, Cavite
                $pdf->SetFont('helvetica', 'B', 10);
                $pdf->Write(0, 'Indang, Cavite', '', 0, 'C', true);
                $pdf->Ln(6);

                // College of Engineering and Information Technology
                $pdf->SetFont('helvetica', 'B', 12);
                $pdf->Write(0, 'COLLEGE OF ENGINEERING AND INFORMATION TECHNOLOGY', '', 0, 'C', true);

                // Output event details in the PDF
                $pdf->SetFont('helvetica', 'B', 16);
                $pdf->Write(0, '', '', 0, 'C', true);
                $pdf->Ln(10);

                $pdf->SetFont('helvetica', 'B', 12);

                $pdf->SetFont('helvetica', '', 12);
                $pdf->Write(0, date('M d, Y', strtotime($event_row['date'])), '', 0, 'R', true);
                $pdf->Ln();

                // For attendees:
                // Fetch user emails associated with this event
                $user_emails_sql = "SELECT ce.user_email, uf.name 
                    FROM calendar_event ce
                    LEFT JOIN user_form uf ON ce.user_email = uf.user_email
                    WHERE ce.date = '$date' AND ce.end_time = '$end_time' AND ce.activity = '$activity'";
                $user_emails_result = $conn->query($user_emails_sql);

                if ($user_emails_result->num_rows > 0) {
                    $pdf->SetFont('helvetica', 'B', 12);
                    $pdf->Write(0, 'Attendees:', "", 0, 'L', true);
                    $pdf->Ln();
                
                    // Move the participant information beside the "To:" label
                    $participants = '';
                    while ($user_row = $user_emails_result->fetch_assoc()) {
                        $participants .= '• ' . ($user_row['name'] ? $user_row['name'] . ' (' . $user_row['user_email'] . ')' : $user_row['user_email']) . "\n";
                    }
                    $pdf->SetFont('helvetica', '', 12); // Set font back to regular for the content
                    $pdf->Write(0, $participants, '', 0, 'L', true);
                    $pdf->Ln();
                }
                
                $pdf->SetFont('helvetica', 'B', 12); // Set font to bold for "From:"
                $pdf->Write(0, 'Implementor: ', '', 0, 'L', true);
                $pdf->SetFont('helvetica', '', 12); // Set font back to regular for the content
                
                // Check if the author's email exists in the user_form table
                $authorEmail = $event_row['author']; // Assuming 'author_email' is the column name in calendar_event table
                $authorName = '';
                
                $user_form_sql = "SELECT name FROM user_form WHERE user_email = '$authorEmail'";
                $user_form_result = $conn->query($user_form_sql);
                
                if ($user_form_result->num_rows > 0) {
                    // If the author's email exists in user_form table, get the name
                    $user_row = $user_form_result->fetch_assoc();
                    $authorName = $user_row['name'];
                }
                
                // Write the author's name or email
                $pdf->Write(0, ($authorName ? $authorName : $authorEmail), '', 0, 'L', true);
                $pdf->Ln();

                $pdf->SetFont('helvetica', 'B', 12);
                $pdf->Write(0, 'Time Started: ', '', 0, 'L', true);
                $pdf->SetFont('helvetica', '', 12);
                $pdf->Write(0, date('h:i A', strtotime($event_row['start_time'])), '', 0, 'L', true);
                $pdf->Ln();

                $pdf->SetFont('helvetica', 'B', 12);
                $pdf->Write(0, 'Location: ', '', 0, 'L', true);
                $pdf->SetFont('helvetica', '', 12);
                $pdf->Write(0, $event_row['location'], '', 0, 'L', true);
                $pdf->Ln();

                $pdf->SetFont('helvetica', 'B', 12);
                $pdf->Write(0, 'Activity: ', '', 0, 'L', true);
                $pdf->SetFont('helvetica', '', 12);
                $pdf->Write(0, $event_row['activity'], '', 0, 'L', true);
                $pdf->Ln();

                // Add Agenda
                $pdf->SetFont('helvetica', 'B', 12);
                $pdf->Write(0, 'Agenda:', '', 0, 'L', true);
                $pdf->SetFont('helvetica', '', 12);
                $pdf->Write(0, $event_row['agenda'], '', 0, 'L', true);
                $pdf->Ln();

                // Save the PDF to a file
                $pdf->Output('meeting_details.pdf', 'D'); // 'D' for download
            }
        } else {
            echo "Meeting details not found for the specified date and time.";
        }
    }
} else {
    echo "Meeting not found for the specified date and time.";
}

// Close database connection
$conn->close();
?>
