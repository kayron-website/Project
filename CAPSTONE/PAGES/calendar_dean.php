<?php
session_start();

if (isset($_SESSION['user_email'])) {
    $user_email = $_SESSION['user_email'];
    $allowed_positions = array("Dean", "Implementor"); // Add "Implementor" to the allowed positions
    $user_position = $_SESSION['user_position']; // Change 'position' to 'user_position'

    if (!in_array($user_position, $allowed_positions)) {
        header("Location: unauthorized.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "scheduler";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_email = $_SESSION['user_email'];

// Query to get events for the logged-in user
$sql = "SELECT ce.date, DATE_FORMAT(ce.date, '%m-%d-%Y') AS formatted_date, TIME_FORMAT(ce.start_time, '%h:%i %p') AS formatted_time, ce.activity, TIME_FORMAT(ce.end_time, '%h:%i %p') AS formatted_etime, ce.location, ce.agenda, uf.name AS author_name, COUNT(*) AS count 
        FROM calendar_event ce 
        LEFT JOIN user_form uf ON ce.author = uf.user_email 
        WHERE ce.author = ? 
        GROUP BY ce.date, ce.start_time, ce.activity, ce.end_time, ce.location, ce.agenda";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();

// Fetch the grouped data
$grouped_events = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scheduler</title>
    <link rel="icon" type="image/x-icon" href="img/logo.png">
    <link rel="stylesheet" href="css/font_1.css">
    <link rel="stylesheet" href="css/practice.css">
	<link rel="stylesheet" href="css/profile.css">
	<link rel="stylesheet" href="css/notif.css">
	<link rel="stylesheet" href="css/button.css">
    <link rel="stylesheet" href="css/officials.css">
    <link href="/CAPSTONE/PAGES/css/fullcalendar.min.css" rel="stylesheet" />
	<script src="/CAPSTONE/PAGES/javascript/jquery.min.js"></script>
	<script src="/CAPSTONE/PAGES/javascript/moment.min.js"></script>
	<script src="/CAPSTONE/PAGES/javascript/fullcalendar.min.js"></script>
	<script src="/CAPSTONE/PAGES/javascript/bootstrap.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/calendar.css">
    <link rel="stylesheet" href="css/clock.css">
	<script src="javascript/fetch_department_members.js"></script>
    <style>
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <div class="web">
        <div class="navigation">
            <ul>
                <li>
                    <a href="">
                        <span class="title"><img src="icon/ceit.png" style="margin-top: -85px; margin-left: -10px; height: 239px; width: 210px;"></span>
                    </a>
                    <hr class="hr">
                </li>
                <li>
                    <a href="calendar_dean.php" id="active">
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" height="30" width="30" viewBox="0 0 448 512" fill="#FC6A03"><path d="M152 24c0-13.3-10.7-24-24-24s-24 10.7-24 24V64H64C28.7 64 0 92.7 0 128v16 48V448c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V192 144 128c0-35.3-28.7-64-64-64H344V24c0-13.3-10.7-24-24-24s-24 10.7-24 24V64H152V24zM48 192h80v56H48V192zm0 104h80v64H48V296zm128 0h96v64H176V296zm144 0h80v64H320V296zm80-48H320V192h80v56zm0 160v40c0 8.8-7.2 16-16 16H320V408h80zm-128 0v56H176V408h96zm-144 0v56H64c-8.8 0-16-7.2-16-16V408h80zM272 248H176V192h96v56z"/></svg>
                        </span>
                        <span class="title" style="color: #FC6A03;">Calendar</span>
                    </a>
                </li>
                <li>
                    <a class="sub-btn">
                        <span class="icon" id="settings">
                        <svg xmlns="http://www.w3.org/2000/svg" height="30" width="30" viewBox="0 0 512 512" fill="#f2f2f2"><path d="M495.9 166.6c3.2 8.7 .5 18.4-6.4 24.6l-43.3 39.4c1.1 8.3 1.7 16.8 1.7 25.4s-.6 17.1-1.7 25.4l43.3 39.4c6.9 6.2 9.6 15.9 6.4 24.6c-4.4 11.9-9.7 23.3-15.8 34.3l-4.7 8.1c-6.6 11-14 21.4-22.1 31.2c-5.9 7.2-15.7 9.6-24.5 6.8l-55.7-17.7c-13.4 10.3-28.2 18.9-44 25.4l-12.5 57.1c-2 9.1-9 16.3-18.2 17.8c-13.8 2.3-28 3.5-42.5 3.5s-28.7-1.2-42.5-3.5c-9.2-1.5-16.2-8.7-18.2-17.8l-12.5-57.1c-15.8-6.5-30.6-15.1-44-25.4L83.1 425.9c-8.8 2.8-18.6 .3-24.5-6.8c-8.1-9.8-15.5-20.2-22.1-31.2l-4.7-8.1c-6.1-11-11.4-22.4-15.8-34.3c-3.2-8.7-.5-18.4 6.4-24.6l43.3-39.4C64.6 273.1 64 264.6 64 256s.6-17.1 1.7-25.4L22.4 191.2c-6.9-6.2-9.6-15.9-6.4-24.6c4.4-11.9 9.7-23.3 15.8-34.3l4.7-8.1c6.6-11 14-21.4 22.1-31.2c5.9-7.2 15.7-9.6 24.5-6.8l55.7 17.7c13.4-10.3 28.2-18.9 44-25.4l12.5-57.1c2-9.1 9-16.3 18.2-17.8C227.3 1.2 241.5 0 256 0s28.7 1.2 42.5 3.5c9.2 1.5 16.2 8.7 18.2 17.8l12.5 57.1c15.8 6.5 30.6 15.1 44 25.4l55.7-17.7c8.8-2.8 18.6-.3 24.5 6.8c8.1 9.8 15.5 20.2 22.1 31.2l4.7 8.1c6.1 11 11.4 22.4 15.8 34.3zM256 336a80 80 0 1 0 0-160 80 80 0 1 0 0 160z"/></svg>
                        </span>
                        <span class="title">Settings</span>
                        <svg class="dropdown" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 256 512"><path d="M246.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-9.2-9.2-22.9-11.9-34.9-6.9s-19.8 16.6-19.8 29.6l0 256c0 12.9 7.8 24.6 19.8 29.6s25.7 2.2 34.9-6.9l128-128z" style="fill: #f2f2f2;"/></svg>
                    </a>
                    <div class="sub-menu">
                        <a href="information_dean.php" class="sub-item">
                            <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 512 512" fill="#f2f2f2"><path d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z"/></svg>
                            <div class="text">Information</div>
                        </a>
                        <a href="status.php" class="sub-item">
                            <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 640 512" fill="#f2f2f2"><path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H392.6c-5.4-9.4-8.6-20.3-8.6-32V352c0-2.1 .1-4.2 .3-6.3c-31-26-71-41.7-114.6-41.7H178.3zM528 240c17.7 0 32 14.3 32 32v48H496V272c0-17.7 14.3-32 32-32zm-80 32v48c-17.7 0-32 14.3-32 32V480c0 17.7 14.3 32 32 32H608c17.7 0 32-14.3 32-32V352c0-17.7-14.3-32-32-32V272c0-44.2-35.8-80-80-80s-80 35.8-80 80z"/></svg>
                            <div class="text">Status</div>
                        </a>
                        <a href="members_dean.php" class="sub-item">
                            <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 384 512" fill="#f2f2f2"><path d="M64 48c-8.8 0-16 7.2-16 16V448c0 8.8 7.2 16 16 16h80V400c0-26.5 21.5-48 48-48s48 21.5 48 48v64h80c8.8 0 16-7.2 16-16V64c0-8.8-7.2-16-16-16H64zM0 64C0 28.7 28.7 0 64 0H320c35.3 0 64 28.7 64 64V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V64zm88 40c0-8.8 7.2-16 16-16h48c8.8 0 16 7.2 16 16v48c0 8.8-7.2 16-16 16H104c-8.8 0-16-7.2-16-16V104zM232 88h48c8.8 0 16 7.2 16 16v48c0 8.8-7.2 16-16 16H232c-8.8 0-16-7.2-16-16V104c0-8.8 7.2-16 16-16zM88 232c0-8.8 7.2-16 16-16h48c8.8 0 16 7.2 16 16v48c0 8.8-7.2 16-16 16H104c-8.8 0-16-7.2-16-16V232zm144-16h48c8.8 0 16 7.2 16 16v48c0 8.8-7.2 16-16 16H232c-8.8 0-16-7.2-16-16V232c0-8.8 7.2-16 16-16z"/></svg>
                            <div class="text">Departments</div>
                        </a>
                    </div>
                    <script>
                        $(document).ready(function(){
                            $('.sub-btn').click(function(){
                                $(this).next('.sub-menu').slideToggle();
                                $(this).find('.dropdown').toggleClass('rotate');
                                $(this).find('#settings').toggleClass('rotate');
                            });
                        });
                    </script>
                </li>
                <li>
                    <a href="profile_dean.php">
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" height="30" width="30" viewBox="0 0 512 512" fill="#f2f2f2"><path d="M399 384.2C376.9 345.8 335.4 320 288 320H224c-47.4 0-88.9 25.8-111 64.2c35.2 39.2 86.2 63.8 143 63.8s107.8-24.7 143-63.8zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm256 16a72 72 0 1 0 0-144 72 72 0 1 0 0 144z"/></svg>
                        </span>
                        <span class="title">Profile</span>
                    </a>
                </li>
                <li>
                    <a href="homepage_dean.php">
                        <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" height="30" width="30" viewBox="0 -960 960 960" fill="#f2f2f2"><path d="M520-600v-240h320v240H520ZM120-440v-400h320v400H120Zm400 320v-400h320v400H520Zm-400 0v-240h320v240H120Zm80-400h160v-240H200v240Zm400 320h160v-240H600v240Zm0-480h160v-80H600v80ZM200-200h160v-80H200v80Zm160-320Zm240-160Zm0 240ZM360-280Z"/></svg>
                        </span>
                        <span class="title">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="logout.php" onclick="return confirm('Are you sure you want to logout?');">
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" height="30" width="30" viewBox="0 0 512 512" fill="#f2f2f2"><path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"/></svg>
                        </span>
                        <span class="title">Logout</span>
                    </a>
                </li>
                <li>
                    <a href="about_dean.php" class="bottom">
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" height="30" width="30" viewBox="0 0 512 512" fill="#f2f2f2"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM216 336h24V272H216c-13.3 0-24-10.7-24-24s10.7-24 24-24h48c13.3 0 24 10.7 24 24v88h8c13.3 0 24 10.7 24 24s-10.7 24-24 24H216c-13.3 0-24-10.7-24-24s10.7-24 24-24zm40-208a32 32 0 1 1 0 64 32 32 0 1 1 0-64z"/></svg>
                        </span>
                        <span class="title">About Us</span>
                    </a>
                </li>
                <li style="margin-top: 155px;">
                    <h7 style="color: #f2f2f2;font-size: 13px;margin-left: 13px;">Calendar Color Meeting:</h7>
                    <div class="legend-item">
                        <div class="color-box orange"></div>
                        <div style="color: #f2f2f2;font-size: 12px;">Event you created</div>
                    </div>
                </li>
                <li>
                    <div class="legend-item">
                        <div class="color-box green"></div>
                        <div style="color: #f2f2f2;font-size: 12px;">Event you're invited</div>
                    </div>
                </li>
                <li>
                    <div class="legend-item">
                        <div class="color-box blue"></div>
                        <div style="color: #f2f2f2;font-size: 12px;">Event for others</div>
                    </div>
                </li>
            </ul>
        </div>
        <div class="mains">
            <div class="topbar">
                <div class="toggle">
                  <svg xmlns="http://www.w3.org/2000/svg" height="25" width="25" viewBox="0 0 448 512"><path d="M0 96C0 78.3 14.3 64 32 64H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 128 0 113.7 0 96zM0 256c0-17.7 14.3-32 32-32H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32c-17.7 0-32-14.3-32-32zM448 416c0 17.7-14.3 32-32 32H32c-17.7 0-32-14.3-32-32s14.3-32 32-32H416c17.7 0 32 14.3 32 32z"/></svg>
                </div>
                <div class="card_2">
                        <div class="backg"></div>
                        <div class="clocks_container">
                            <div class="clocks__content grid">
                                <div class="clocks__text">
                                    <div class="clocks__text-hour" id="text-hour"></div>
                                    <div class="clocks__text-minutes" id="text-minutes"></div>
                                    <div class="clocks__text-ampm" id="text-ampm"></div>
                                </div>
                                <div class="clocks__date">
                                    <span id="dates-day"></span>
                                    <span id="dates-month"></span>
                                    <span id="dates-year"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script src="javascript/clock.js"></script>
                <div class="notify">
                    <button class="button" onclick="toggleActivities()">
                        <svg viewBox="0 0 448 512" class="bell">
                            <path d="M224 0c-17.7 0-32 14.3-32 32V49.9C119.5 61.4 64 124.2 64 200v33.4c0 45.4-15.5 89.5-43.8 124.9L5.3 377c-5.8 7.2-6.9 17.1-2.9 25.4S14.8 416 24 416H424c9.2 0 17.6-5.3 21.6-13.6s2.9-18.2-2.9-25.4l-14.9-18.6C399.5 322.9 384 278.8 384 233.4V200c0-75.8-55.5-138.6-128-150.1V32c0-17.7-14.3-32-32-32zm0 96h8c57.4 0 104 46.6 104 104v33.4c0 47.9 13.9 94.6 39.7 134.6H72.3C98.1 328 112 281.3 112 233.4V200c0-57.4 46.6-104 104-104h8zm64 352H224 160c0 17 6.7 33.3 18.7 45.3s28.3 18.7 45.3 18.7s33.3-6.7 45.3-18.7s18.7-28.3 18.7-45.3z"></path>
                        </svg>
                        <?php
                        $query = "SELECT COUNT(*) AS schedule_count FROM table_sched WHERE user_email = '$user_email'";
                        $result = mysqli_query($conn, $query);

                        if ($result) {
                            $row = mysqli_fetch_assoc($result);
                            $schedule_count = $row['schedule_count'];

                            if ($schedule_count > 0) {
                                echo '<span class="schedule-count">' . $schedule_count . '</span>';
                            }
                        }
                        ?>
                    </button>
                    <div class="popup" id="activityPopup">
                        <div class="popup-content">
                            <ul>
                                <?php
                                    if ($schedule_count > 0) {
                                        $query = "SELECT activity, start_time, date FROM table_sched WHERE user_email = '$user_email'";
                                        $result = mysqli_query($conn, $query);

                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $start_time_24hour = $row['start_time'];
                                            $start_time_12hour = date("h:i A", strtotime($start_time_24hour));
                                            $activity = $row['activity'];
                                            $date = date("F j, Y", strtotime($row['date']));
                                        
                                            echo '<li style="padding: 5px">You have a ' . $activity . ' at ' . $start_time_12hour . ' on ' . $date;
                                            echo '</li>';
                                            echo '<hr>';
                                        }
                                    } else {
                                        echo '<li>No Meeting Today</li>';
                                    }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <script>
                    function toggleActivities() {
                        var popup = document.getElementById('activityPopup');
                        popup.style.display = (popup.style.display === 'block') ? 'none' : 'block';
                    }

                    var activityPopup = document.getElementById('activityPopup');
                    var notifyButton = document.querySelector('.notify .button');

                    function toggleActivities() {
                        activityPopup.classList.toggle('show');
                    }

                    document.addEventListener('click', function(event) {
                        if (!activityPopup.contains(event.target) && event.target !== notifyButton) {
                            activityPopup.classList.remove('show');
                        }
                    });
                </script>
                <?php
                    $userEmail = $_SESSION['user_email'];

                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "scheduler";

                    $conn = new mysqli($servername, $username, $password, $dbname);

                    $sql = "SELECT nickname, user_type, image FROM user_form WHERE user_email = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $userEmail);
                    $stmt->execute();
                    $stmt->store_result();

                    if ($stmt->num_rows > 0) {
                        // If a record is found, retrieve the details
                        $stmt->bind_result($nickname, $userType, $image);
                        $stmt->fetch();

                        // Display user information
                        echo '<div class="info">
                                <p>Hello, <b>'.$nickname.'</b></p>
                                <small class="text-muted">'.$userType.'</small>
                            </div>';

                        // Check if image field is empty
                        if (!empty($image)) {
                            // Display the image from the database
                            echo '<div class="user">
                                    <img src="img/'.$image.'" alt="">
                                </div>';
                        } else {
                            // If image field is empty, display a default image
                            echo '<div class="user">
                                    <img src="img/img.png" alt="">
                                </div>';
                        }
                    } else {
                        echo "User details not found";
                    }
                ?>
            </div>
            <div class="details_4">
                <div class="card">
                    <select id="filter" style="border-radius: 8px;color: #f2f2f2;background: #2a2a2a;border-color: #2a2a2a;padding: 5px;margin-top: 5px;margin-bottom: 5px;">
                        <option value="all">All Meetings</option>
                        <option value="my">My Meetings</option>
                    </select>
                    <div class="row">
                        <div class="col-lg-13">
                            <div id="calendar"></div>
                        </div>
                    </div>
                    <form method="post" enctype="multipart/form-data">
                        <div class="modal fade" id="event_entry_modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" style="margin-top: 145px;">
                            <div class="modal-dialog modal-md" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalLabel">Create Event</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="img-container">
                                            <div class="rows">
                                                <div class="col-sm-6">  
                                                    <div class="form-group">
                                                    <label for="date"></label>
                                                    <input type="hidden" name="date" id="date" class="form-control onlydatepicker" placeholder="Event start date">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">  
                                                    <div class="form-group">
                                                    <label for="end_date"></label>
                                                    <input type="hidden" name="end_date" id="end_date" class="form-control onlydatepicker" placeholder="Event end date">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="rows" style="margin-top: -40px;display: flex;width: 695px;">
                                                <div class="col-sm-6">  
                                                    <div class="form-group">
                                                    <label for="start_time">Event Time</label>
                                                    <input type="time" name="start_time" id="start_time" class="form-control " placeholder="Start Time" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6" style="margin-left: -30px;">  
                                                    <div class="form-group">
                                                    <label for="end_time">End</label>
                                                    <input type="time" name="end_time" id="end_time" class="form-control" placeholder="End Time" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6" style="margin-left: -30px;"> 
                                                    <label for="location">Event Place</label>
                                                    <input type="text" name="location" id="location" class="form-control" placeholder="Enter event place" required>
                                                </div>
                                                <div class="col-sm-6" style="margin-left: 8px;"> 
                                                    <label for="activity">Activity</label>
                                                    <input type="text" name="activity" id="activity" class="form-control" placeholder="Enter activity">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="agenda">Agenda</label>
                                                <textarea type="textbox" name="agenda" id="agenda" rows="4" cols="50" style="height: 120px;width: 1325px;"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="attachment">Attachment:</label><br>
                                                <input type="file" id="attachment" name="attachment">
                                            </div>
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#departmentModal">
                                            Attendees Selection
                                            </button>
                                            <div class="modal fade" id="departmentModal" tabindex="-1" aria-labelledby="departmentModalLabel" aria-hidden="true" style="width: 78%; height: 655px; display: block; margin-left: 100px; margin-top: 3px; padding-right: 6px;">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-contents" style="width: 390px;position: relative;display: flex;-webkit-box-orient: vertical;-webkit-box-direction: normal;-ms-flex-direction: column;flex-direction: column;pointer-events: auto;background-color: #fff;background-clip: padding-box;border: 1px solid rgba(0, 0, 0, 0.2);border-radius: 0.3rem;outline: 0;height: 625px;margin-top: -29px;">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="departmentModalLabel">Select Attendees</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body" style="width: 371px;">
                                                        <div class="container">
                                                            <select id="departmentFilter" onchange="toggleDropdowns()" style="display: block;width: 100%;height: 40px;text-align: center;border-radius: 10px;background: #2a2a2a;color: #f2f2f2;">
                                                                <?php
                                                                $departments = array(
                                                                    'Officials' => 'i',
                                                                    'DIT' => 'it',
                                                                    'DIET' => 'et',
                                                                    'DAFE' => 'fe',
                                                                    'DCEE' => 'ee',
                                                                    'DCEA' => 'ea'
                                                                );

                                                                foreach ($departments as $department => $abbrev) {
                                                                    echo '<option value="' . $abbrev . '">' . $department . '</option>';
                                                                }
                                                                ?>
                                                            </select>

                                                            <?php
                                                            foreach ($departments as $department => $abbrev) {
                                                                ?>
                                                                <div class="dropdowns <?php echo $department !== 'Officials' ? 'hidden' : ''; ?>" id="dropdown_<?php echo $abbrev; ?>">
                                                                    <button class="dropbtn" id="dropdownBtn_<?php echo $abbrev; ?>" onclick="rotateSVG('<?php echo $abbrev; ?>')">
                                                                        <?php echo $department; ?><span id="selectedCount_<?php echo $abbrev; ?>" class="count"></span>
                                                                    </button>
                                                                    <!-- Search input for the department -->
                                                                    <input type="text" id="search_<?php echo $abbrev; ?>" class="form-control" placeholder="Search <?php echo $department; ?>" style="font-size: 12px;width: 356px;height: 23px;border-radius: 0px 0px 5px 5px;">
                                                                    <div class="dropdown-content" id="dropdownContent_<?php echo $abbrev; ?>">
                                                                        <?php
                                                                        if ($department !== 'Officials') {
                                                                            // For departments other than 'Officials', retrieve data based solely on 'department' column
                                                                            $sql = "SELECT id, name, user_email FROM user_form WHERE department = '$department'";
                                                                        } else {
                                                                            // For 'Officials' department, retrieve data based on 'position' column
                                                                            $allowed_position_types = [
                                                                                'Dean',
                                                                                'Chairperson',
                                                                                'College Secretary',
                                                                                'College Budget Officer',
                                                                                'College Registrar',
                                                                                'Assistant College Registrar',
                                                                                'College MIS/PIO Officer',
                                                                                'Coordinator Research Services, Coordinator Graduate Programs',
                                                                                'Coordinator, Extension Services',
                                                                                'Coordinator, R&E Monitoring and Evaluation Unit',
                                                                                'College OJT Coordinator',
                                                                                'Coordinator, College Quality Assurance and Accreditation',
                                                                                'Asst. Coordinator, College Quality Assurance and Accreditation',
                                                                                'Coordinator, Knowledge Management Unit',
                                                                                'Coordinator, Gender and Development Program',
                                                                                'Coordinator for Sports and Socio-Cultural Development',
                                                                                'College Review Coordinator for BSABE and BSCE',
                                                                                'College Review Coordinator for BSECE and BSEE',
                                                                                'College Guidance Counselor for BSABE, BSIT, BSCS, and Architecture',
                                                                                'College Guidance Counselor for BSCE, BSECE, BSEE, BSCpE, BSIE and BIT programs',
                                                                                'College Job Placement Officer',
                                                                                'College Property Custodian',
                                                                                'College Canvasser',
                                                                                'College Inspector',
                                                                                'In-charge, College Reading Room',
                                                                                'In-charge, Material Testing Laboratory',
                                                                                'In-charge, Industrial Automation Center',
                                                                                'In-charge, APPROTEC Center',
                                                                                'College Civil Security Officer',
                                                                                'In-charge, Simulation and Math Laboratory',
                                                                                'CCL Head',
                                                                                'University Web Master'
                                                                            ];

                                                                            // Convert the position types array into a comma-separated string for the SQL query
                                                                            $position_types_string = "'" . implode("', '", $allowed_position_types) . "'";

                                                                            // Modify SQL query to include position filtering
                                                                            $sql = "SELECT id, name, user_email FROM user_form WHERE position = 'Officials' AND position_type IN ($position_types_string)";
                                                                        }

                                                                        $result = $conn->query($sql);

                                                                        if ($result->num_rows > 0) {
                                                                            echo '<label><input type="checkbox" name="select_all" id="select-all_' . $abbrev . '">Select All</label>';
                                                                            while ($row = $result->fetch_assoc()) {
                                                                                echo '<label><input type="checkbox" name="' . strtolower($department) . '[]" value="' . $row["id"] . '" data-user-email="' . $row["user_email"] . '">' . $row["name"] . '</label>';
                                                                            }
                                                                        } else {
                                                                            echo "No $department found in the database.";
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                        <script>
                                                            function toggleDropdowns() {
                                                                var filter = document.getElementById('departmentFilter').value;
                                                                var dropdowns = document.getElementsByClassName('dropdowns');

                                                                for (var i = 0; i < dropdowns.length; i++) {
                                                                    var abbrev = dropdowns[i].id.replace('dropdown_', '');
                                                                    if (filter === 'all' || abbrev === filter) {
                                                                        dropdowns[i].classList.remove('hidden');
                                                                    } else {
                                                                        dropdowns[i].classList.add('hidden');
                                                                    }
                                                                }
                                                            }

                                                            // Function to filter options based on search input
                                                            function filterOptions(abbrev) {
                                                                var input, filter, dropdown, options, i, txtValue;
                                                                input = document.getElementById('search_' + abbrev);
                                                                filter = input.value.toUpperCase();
                                                                dropdown = document.getElementById('dropdownContent_' + abbrev);
                                                                options = dropdown.getElementsByTagName('label');
                                                                for (i = 0; i < options.length; i++) {
                                                                    txtValue = options[i].textContent || options[i].innerText;
                                                                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                                                                        options[i].style.display = "";
                                                                    } else {
                                                                        options[i].style.display = "none";
                                                                    }
                                                                }
                                                            }

                                                            // Attach event listeners to the search input fields
                                                            <?php
                                                            foreach ($departments as $department => $abbrev) {
                                                                echo "document.getElementById('search_$abbrev').addEventListener('input', function() {
                                                                    filterOptions('$abbrev');
                                                                });";
                                                            }
                                                            ?>
                                                        </script>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary close-department-modal">Close</button>
                                                        <!-- You can add additional buttons here if needed -->
                                                    </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <script>
                                                // JavaScript to handle closing of the departmentModal
                                                document.addEventListener('click', function(event) {
                                                    if (event.target.classList.contains('close-department-modal')) {
                                                        // Check if the click event originated from a close button within departmentModal
                                                        var departmentModal = document.getElementById('departmentModal');
                                                        if (departmentModal.contains(event.target)) {
                                                            $('#departmentModal').modal('hide'); // Close the departmentModal
                                                        }
                                                    }
                                                });
                                            </script>
                                            <script>
                                                var selectedEmailsMap = {};

                                                // Function to count checked checkboxes and update count display
                                                function updateCheckedCount(category) {
                                                    var checkboxes = document.querySelectorAll(`#dropdownContent_${category} input[type="checkbox"]`);
                                                    var checkedCount = 0;
                                                    var selectAllCheckbox = document.getElementById(`select-all_${category}`);
                                                    var countingStarted = false;

                                                    checkboxes.forEach(function (checkbox) {
                                                        if (!countingStarted && checkbox === selectAllCheckbox) {
                                                            countingStarted = true;
                                                            return;
                                                        }

                                                        if (countingStarted && checkbox.checked) {
                                                            checkedCount++;
                                                        }
                                                    });

                                                    var countSpan = document.getElementById(`selectedCount_${category}`);
                                                    countSpan.textContent = checkedCount > 0 ? `(${checkedCount})` : '';

                                                    // Update the global variable with the selected emails for this category
                                                    updateSelectedEmails(category);
                                                }

                                                function handleSelectAllChange(category) {
                                                    var checkboxes = document.querySelectorAll(`#dropdownContent_${category} input[type="checkbox"]:not(#select-all_${category})`);
                                                    var selectAllCheckbox = document.getElementById(`select-all_${category}`);
                                                    var selectedEmails = [];

                                                    checkboxes.forEach(function (checkbox) {
                                                        checkbox.checked = selectAllCheckbox.checked;
                                                        if (checkbox.checked && checkbox.value !== 'select-all') {
                                                            selectedEmails.push(checkbox.getAttribute('data-user-email'));
                                                        }
                                                    });

                                                    updateCheckedCount(category);

                                                    // Store the selected emails in the global variable
                                                    selectedEmailsMap[category] = selectedEmails;
                                                }

                                                function updateSelectedEmails(category) {
                                                    var selectedUserEmails = [];
                                                    var checkboxes = document.querySelectorAll(`#dropdownContent_${category} input[type="checkbox"]:checked`);
                                                    checkboxes.forEach(function (checkbox) {
                                                        if (checkbox.value !== 'select-all') {
                                                            selectedUserEmails.push(checkbox.getAttribute('data-user-email'));
                                                        }
                                                    });

                                                    // Update the global variable with the selected emails for this category
                                                    selectedEmailsMap[category] = selectedUserEmails;
                                                }

                                                function handleDropdownBtnClick(category) {
                                                    var dropdownContent = document.getElementById(`dropdownContent_${category}`);
                                                    dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
                                                }

                                                function handleIndividualCheckboxChange(category) {
                                                    var checkboxes = document.querySelectorAll(`#dropdownContent_${category} input[type="checkbox"]:not(#select-all_${category})`);
                                                    var selectAllCheckbox = document.getElementById(`select-all_${category}`);

                                                    checkboxes.forEach(function (checkbox) {
                                                        checkbox.addEventListener('change', function () {
                                                            var allChecked = true;
                                                            checkboxes.forEach(function (chk) {
                                                                if (chk !== selectAllCheckbox && !chk.checked) {
                                                                    allChecked = false;
                                                                }
                                                            });
                                                            selectAllCheckbox.checked = allChecked;
                                                            updateCheckedCount(category);
                                                        });
                                                    });
                                                }

                                                // Usage example
                                                ['i', 'it', 'et', 'fe', 'ee', 'ea'].forEach(function (category) {
                                                    var dropdownContent = document.getElementById(`dropdownContent_${category}`);
                                                    dropdownContent.style.display = 'block'; // Show the dropdown content initially

                                                    var checkboxes = dropdownContent.querySelectorAll('input[type="checkbox"]');

                                                    checkboxes.forEach(function (checkbox) {
                                                        checkbox.addEventListener('change', function () {
                                                            updateCheckedCount(category);
                                                        });
                                                    });

                                                    document.getElementById(`select-all_${category}`).addEventListener('change', function () {
                                                        handleSelectAllChange(category);
                                                    });

                                                    handleIndividualCheckboxChange(category);
                                                });

                                                // Function to handle dropdown button click
                                                function handleDropdownBtnClick(category) {
                                                    var dropdownContent = document.getElementById(`dropdownContent_${category}`);
                                                    dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
                                                }
                                            </script>
                                        </div>
                                        <div class="modal-footer">
                                            <input type="button" class="btn btn-primary" value="Save" onclick="saveEvent()" style="border-color: #2a2a2a;background: #2a2a2a;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="modal fade" id="eventDetailsModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-md" role="document">
                            <div class="modal-content-view">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalLabel">Event Details</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                <p><strong>Implementor:</strong> <span id="author_display"></span></p>
                                    <p><strong>Date:</strong> <span id="date_details"></span></p>
                                    <p><strong>Time:</strong> <span id="start_time_display"></span></p>
                                    <p style="height: min-content;"><strong>Venue:</strong> <span id="location_display"></span></p>
                                    <p style="height: min-content;"><strong>Agenda:</strong> <span id="agenda_display" style="max-width: 215px;overflow-x: auto;border-radius: 10px;background: #ffeae0;"></span></p>
                                    <p><strong>Participants:</strong> <span id="user_emails_display" style="max-width: 292px;max-height: 180px;border-radius: 10px;background: #ffeae0;"></span></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" id="edit_event_button">Edit</button>
                                    <button type="button" class="btn btn-danger" id="delete_event_button">Delete</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="editEventModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-md" role="document">
                            <div class="modal-content" style="width: 69%; height: 100%; left: 550px;">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalLabel">Edit Event</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <form id="editEventForm">
                                    <div class="modal-body">
                                        <input type="hidden" id="edit_event_id" name="event_id">
                                        <div class="form-group">
                                            <label for="edit_date">Date</label>
                                            <input type="date" id="edit_date" name="new_date" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="edit_start_time">Start Time</label>
                                            <input type="time" id="edit_start_time" name="new_start_time" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="edit_end_time">End Time</label>
                                            <input type="time" id="edit_end_time" name="new_end_time" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="edit_activity">Activity</label>
                                            <input type="text" id="edit_activity" name="new_activity" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="edit_location">Location</label>
                                            <input type="text" id="edit_location" name="new_location" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="edit_agenda">Agenda</label>
                                            <textarea id="edit_agenda" name="new_agenda" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary" id="save_edit_button">Save changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="event_details_modal" tabindex="-1" role="dialog" aria-labelledby="eventDetailsModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content" style="margin-left: 100px;height: 100%;">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="eventDetailsModalLabel">Event Details</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" id="eventDetailsContent">
                                    <!-- Event details will be populated here -->
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Back</button>
                                    <button type="button" class="btn btn-primary" id="modalOKButton">OK</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="javascript/smtps.js"></script>
<script src="javascript/sidebar.js"></script>
<script>
	    $(document).ready(function() {
            var events = [];
            var loggedInUserName = ''; // Variable to store the logged-in user's name

            function display_events() {
                $.ajax({
                    url: 'display_event.php',
                    dataType: 'json',
                    success: function(response) {
                        var result = response.data;
                        loggedInUserName = response.logged_in_user_name; // Get the logged-in user's name

                        events = result.map(item => ({
                            event_id: item.event_id,
                            title: item.title,
                            start: item.start,
                            end: item.end,
                            color: item.color,
                            url: item.url,
                            activity: item.title,
                            agenda: item.agenda,
                            date: moment(item.start).format('YYYY-MM-DD'),
                            start_time: item.start_time,
                            end_time: item.end_time,
                            location: item.location,
                            author: item.author,
                            user_emails: item.user_emails
                        }));

                        // Render the calendar after fetching events
                        renderCalendar(events);
                    },
                    error: function(xhr, status) {
                        console.error('Failed to fetch events:', status);
                        alert('Failed to fetch events. Please try again.');
                    }
                });
            }

            function renderCalendar(events) {
                $('#calendar').fullCalendar('destroy'); // Clear existing calendar
                $('#calendar').fullCalendar({
                    defaultView: 'month',
                    timeZone: 'local',
                    editable: true,
                    selectable: true,
                    selectHelper: true,
                    select: function (start, end) {
                        // Check if the selected range includes any Sundays and prevent action if true
                        var sundaySelected = false;
                        var currentDate = start.clone();
                        while (currentDate.isBefore(end)) {
                            if (currentDate.day() === 0) {
                                sundaySelected = true;
                                break;
                            }
                            currentDate.add(1, 'day');
                        }

                        if (sundaySelected) {
                            alert('Events cannot start or end on Sundays.');
                            return;
                        }

                        var currentDate = moment(); // Get the current date
                        if (start.isBefore(currentDate) && !start.isSame(currentDate, 'day')) {
                            alert('Cannot select past dates.');
                            return;
                        }
                        $('#date').val(moment(start).format('YYYY-MM-DD'));
                        $('#end_date').val(moment(end).format('YYYY-MM-DD'));
                        $('#event_entry_modal').modal('show');
                    },
                    events: events, // Pass events array
                    header: {
                        left: 'prev',
                        center: 'title',
                        right: 'next'
                    },
                    eventClick: function(calEvent, jsEvent, view) {
                        // Display event details
                        $('#eventDetailsModal').modal('show');
                        $('#date_details').text(moment(calEvent.date).format('MMMM D YYYY'));
                        $('#start_time_display').text(moment(calEvent.start_time, 'HH:mm').format('hh:mm A'));
                        $('#location_display').text(calEvent.location);
                        $('#agenda_display').html('<p>' + calEvent.agenda + '</p>');
                        $('#author_display').text(calEvent.author);

                        var userEmails = calEvent.user_emails;
                        var userEmailsHtml = '';
                        if (userEmails && userEmails.length > 0) {
                            userEmailsHtml += '<ul>';
                            var maxDisplay = Math.min(5, userEmails.length);
                            for (var i = 0; i < maxDisplay; i++) {
                                userEmailsHtml += '<li>' + userEmails[i] + '</li>';
                            }
                            userEmailsHtml += '</ul>';
                            if (userEmails.length > 5) {
                                userEmailsHtml += '<button class="btn btn-link view-all-emails" style="margin-left: 163px;color: #f2f2f2;background: #FC6A03;padding: 4px;font-size: 12px;">View All</button>';
                            }
                        } else {
                            userEmailsHtml = 'No user emails associated with this event.';
                        }
                        $('#user_emails_display').html(userEmailsHtml);

                        $('.view-all-emails').click(function() {
                            var allUserEmailsHtml = '<ul>';
                            $.each(userEmails, function(index, email) {
                                allUserEmailsHtml += '<li>' + email + '</li>';
                            });
                            allUserEmailsHtml += '</ul>';

                            var modalHtml = `
                                <div class="modal fade" id="viewAllEmailsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-contents" style="background: white;">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">All Participants</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">${allUserEmailsHtml}</div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>`;

                            $(document.body).append(modalHtml);
                            $('#viewAllEmailsModal').modal('show');

                            $('#viewAllEmailsModal').on('hidden.bs.modal', function() {
                                $(this).remove();
                            });
                        });

                        // Determine if the event is in the past
                        var eventStartDate = moment(calEvent.start);
                        var currentDate = moment();

                        if (eventStartDate.isBefore(currentDate, 'day')) {
                            // Event is in the past, hide Edit and Delete buttons
                            $('#edit_event_button').hide();
                            $('#delete_event_button').hide();
                        } else {
                            // Event is today or in the future, check if the logged-in user is the author
                            if (calEvent.author === loggedInUserName) {
                                // Logged-in user is the author, show Edit and Delete buttons
                                $('#edit_event_button').show().off('click').on('click', function() {
                                    $('#edit_event_id').val(calEvent.event_id);
                                    $('#edit_start_time').val(moment(calEvent.start_time, 'HH:mm:ss').format('HH:mm'));
                                    $('#edit_end_time').val(moment(calEvent.end_time, 'HH:mm:ss').format('HH:mm'));
                                    $('#edit_activity').val(calEvent.activity);
                                    $('#edit_location').val(calEvent.location);
                                    $('#edit_agenda').val(calEvent.agenda);
                                    $('#editEventModal').modal('show');
                                });

                                $('#delete_event_button').show().off('click').on('click', function() {
                                    var event_id = calEvent.event_id;

                                    // Show a confirmation dialog before proceeding
                                    if (confirm('Are you sure you want to delete this event?')) {
                                        $.ajax({
                                            url: 'delete_calendar.php',
                                            type: 'POST',
                                            dataType: 'json',
                                            data: { event_id: event_id },
                                            success: function(response) {
                                                if (response.success) {
                                                    $('#calendar').fullCalendar('refetchEvents');
                                                    $('#eventDetailsModal').modal('hide');
                                                    // Reload the window after successful deletion
                                                    window.location.reload();
                                                } else {
                                                    alert('Failed to delete event. Please try again.');
                                                }
                                            },
                                            error: function(xhr, status) {
                                                console.error('Failed to delete event:', status);
                                                alert('Failed to delete event. Please try again.');
                                            }
                                        });
                                    }
                                });
                            } else {
                                // Logged-in user is not the author, hide Edit and Delete buttons
                                $('#edit_event_button').hide();
                                $('#delete_event_button').hide();
                            }
                        }
                    }
                });
            }

            // Initialize the calendar even if there are no events initially
            renderCalendar([]);

            // Fetch and display events initially
            display_events();

            // Filter events based on the selected option
            $('#filter').change(function() {
                var filterValue = $(this).val();

                if (filterValue === 'my') {
                    var filteredEvents = events.filter(function(event) {
                        return event.user_emails.includes(loggedInUserName);
                    });
                    if (filteredEvents.length > 0) {
                        renderCalendar(filteredEvents);
                    } else {
                        // No events found alert
                        $('#calendar').fullCalendar('removeEvents'); // Remove all existing events
                        alert('No events created for you.');
                    }
                } else {
                    renderCalendar(events);
                }
            });
        });

        $('#save_edit_button').click(function() {
            var eventId = $('#edit_event_id').val();
            var date = $('#edit_date').val();
            var startTime = $('#edit_start_time').val();
            var endTime = $('#edit_end_time').val();
            var activity = $('#edit_activity').val();
            var location = $('#edit_location').val();
            var agenda = $('#edit_agenda').val();

            $.ajax({
                url: 'update_event.php',
                type: 'POST',
                data: {
                    event_id: eventId,
                    new_date: date,
                    new_start_time: startTime,
                    new_end_time: endTime,
                    new_activity: activity,
                    new_location: location,
                    new_agenda: agenda
                },
                success: function(response) {
                    try {
                        var data = JSON.parse(response);
                        if (data.success) {
                            alert('Event updated successfully.');
                            $('#editEventModal').modal('hide');
                            // Optionally, you can refresh the calendar here
                            $('#calendar').fullCalendar('refetchEvents');
                            // Reload the window after successful update
                            window.location.reload();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    } catch (error) {
                        console.error('Error parsing JSON:', error);
                        alert('Unexpected response from server.');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('AJAX request failed:', textStatus);
                    alert('Failed to update event: ' + textStatus);
                }
            });
        });


    // Function to send invitations to selected attendees
    function sendInvitations(eventData) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'send_invitations.php', true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    // Directly use the response text (plain text)
                    var response = xhr.responseText;

                    // Log the response for debugging
                    console.log('Response from send_invitations.php:', response);

                    // Show a confirmation message using a custom popup
                    showCustomPopup(response);

                    window.location.href = 'calendar_dean.php';
                } else {
                    // Handle errors, if any
                    console.error('Error: ' + xhr.status);
                }
            }
        };

        // Send the event data
        xhr.send(JSON.stringify(eventData));
    }

    function showCustomPopup(message) {
        // Create a custom popup element
        var popup = document.createElement('div');
        popup.className = 'custom-popup';
        popup.textContent = message;

        // Style the popup
        popup.style.position = 'fixed';
        popup.style.top = '50%';
        popup.style.left = '50%';
        popup.style.transform = 'translate(-50%, -50%)';
        popup.style.padding = '20px';
        popup.style.background = 'rgba(0, 0, 0, 0.7)';
        popup.style.color = '#fff';
        popup.style.borderRadius = '5px';
        popup.style.zIndex = '9999';

        // Append the popup to the body
        document.body.appendChild(popup);

        // Remove the popup after 2 seconds
        setTimeout(function () {
            popup.parentNode.removeChild(popup);
        }, 2000);
    }

    // Function to save event details and send invitations
    function saveEvent() {
        // Retrieve form data
        var date = document.getElementById('date').value;
        var endDate = document.getElementById('end_date').value;
        var startTime = document.getElementById('start_time').value;
        var endTime = document.getElementById('end_time').value;
        var activity = document.getElementById('activity').value;
        var location = document.getElementById('location').value;
        var agenda = document.getElementById('agenda').value; // Retrieve agenda value

        // Retrieve selected user emails from the global variable
        var selectedUserEmails = [];
        Object.values(selectedEmailsMap).forEach(function (categoryEmails) {
            selectedUserEmails = selectedUserEmails.concat(categoryEmails);
        });

        // Handle file upload
        var attachmentInput = document.getElementById('attachment');
        var attachmentFile = attachmentInput.files[0]; // Get the first file selected by the user

        if (attachmentFile) {
            // Read the file content
            var reader = new FileReader();
            reader.onload = function(event) {
                var attachmentData = event.target.result; // File content as base64 string

                // Prepare data to be sent
                var eventData = {
                    date: date,
                    end_date: endDate,
                    start_time: startTime,
                    end_time: endTime,
                    activity: activity,
                    location: location,
                    agenda: agenda, // Add agenda field here
                    selected_emails: selectedUserEmails,
                    attachment_data: attachmentData, // Add attachment data here
                    attachment_name: attachmentFile.name // Add attachment name here
                };

                // Display modal with event details
                displayEventModal(eventData);
            };

            // Read the file as a Data URL
            reader.readAsDataURL(attachmentFile);
        } else {
            // Prepare data to be sent without attachment
            var eventData = {
                date: date,
                end_date: endDate,
                start_time: startTime,
                end_time: endTime,
                activity: activity,
                location: location,
                agenda: agenda, // Add agenda field here
                selected_emails: selectedUserEmails
            };

            // Display modal with event details
            displayEventModal(eventData);
        }
    }

    function displayEventModal(eventData) {
        // AJAX request to fetch user names based on user emails
        $.ajax({
            url: 'fetch_user_names.php',
            method: 'POST',
            data: { user_emails: eventData.selected_emails },
            dataType: 'json',
            success: function(response) {
                var names = response.names;

                // Construct HTML for event details
                var detailsHTML = '<p><strong>Date:</strong> ' + eventData.date + '</p>' +
                    '<p><strong>Time:</strong> ' + eventData.start_time + ' - ' + eventData.end_time + '</p>' +
                    '<p><strong>Location:</strong> ' + eventData.location + '</p>' +
                    '<p><strong>Activity:</strong> ' + eventData.activity + '</p>' +
                    '<p><strong>Agenda:</strong> ' + eventData.agenda + '</p>';

                // Construct HTML for selected user names
                var emailsHTML = '<p><strong>Selected Participants:</strong></p><ul style="max-height: 160px;overflow-y: auto;">';
                eventData.selected_emails.forEach(function(email) {
                    emailsHTML += '<li>' + (names[email] || email) + '</li>';
                });
                emailsHTML += '</ul>';

                // Populate the modal content with event details and selected user names
                document.getElementById('eventDetailsContent').innerHTML = detailsHTML + emailsHTML;

                // Show the modal
                $('#event_details_modal').modal('show');

                // Attach event listener to the OK button
                document.getElementById('modalOKButton').onclick = function() {
                    saveEventToDatabase(eventData);
                    $('#event_details_modal').modal('hide'); // Hide the modal after saving
                };
            },
            error: function(xhr, status, error) {
                console.error('Failed to fetch user names:', error);
                // Fallback to displaying emails if names cannot be fetched
                var emailsHTML = '<p><strong>Selected Participants:</strong></p><ul style="max-height: 160px;overflow-y: auto;">';
                eventData.selected_emails.forEach(function(email) {
                    emailsHTML += '<li>' + email + '</li>';
                });
                emailsHTML += '</ul>';

                // Populate the modal content with event details and selected user emails
                var detailsHTML = '<p><strong>Date:</strong> ' + eventData.date + '</p>' +
                    '<p><strong>Time:</strong> ' + eventData.start_time + ' - ' + eventData.end_time + '</p>' +
                    '<p><strong>Location:</strong> ' + eventData.location + '</p>' +
                    '<p><strong>Activity:</strong> ' + eventData.activity + '</p>' +
                    '<p><strong>Agenda:</strong> ' + eventData.agenda + '</p>';

                document.getElementById('eventDetailsContent').innerHTML = detailsHTML + emailsHTML;

                // Show the modal
                $('#event_details_modal').modal('show');

                // Attach event listener to the OK button
                document.getElementById('modalOKButton').onclick = function() {
                    saveEventToDatabase(eventData);
                    $('#event_details_modal').modal('hide'); // Hide the modal after saving
                };
            }
        });
    }

    function saveEventToDatabase(eventData) {
        // Send data to save_event.php using AJAX
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'save_event.php', true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    // Directly use the response text (plain text)
                    var response = xhr.responseText;

                    // Log the response for debugging
                    console.log('Response from save_event.php:', response);

                    // Send event data to send_invitations.php
                    sendInvitations(eventData);

                    // Show a confirmation message for saving event
                    alert(response);
                } else {
                    // Handle errors, if any
                    console.error('Error: ' + xhr.status);
                }
            }
        };

        // Send the request
        xhr.send(JSON.stringify(eventData));
    }

    // Get the input elements
    var timeInput_start = document.getElementById('start_time');
    var timeInput_end = document.getElementById('end_time');

    // Set minimum and maximum time
    var minTime = '07:00'; // 7:00 AM
    var maxTime = '18:00'; // 6:00 PM

    // Minimum difference in minutes
    var minDifference = 20; // 30 minutes

    // Function to convert time string to minutes since midnight
    function timeToMinutes(time) {
        var [hours, minutes] = time.split(':').map(Number);
        return hours * 60 + minutes;
    }

    // Function to get the current time in minutes since midnight
    function getCurrentTimeMinutes() {
        var now = new Date();
        return now.getHours() * 60 + now.getMinutes();
    }

    // Event listener for start time
    timeInput_start.addEventListener('input', function() {
        var selectedTime = this.value;

        // Validate start time
        if (selectedTime < minTime || selectedTime > maxTime) {
            alert('Please select a time between 7:00 AM and 6:00 PM.');
            this.value = '';
            return;
        }

        // Validate end time if it is set
        if (timeInput_end.value) {
            var endTimeMinutes = timeToMinutes(timeInput_end.value);
            
            if (endTimeMinutes <= selectedTimeMinutes) {
                alert('End time must be after start time.');
                timeInput_end.value = '';
            } else if (endTimeMinutes - selectedTimeMinutes < minDifference) {
                alert(`End time must be at least ${minDifference} minutes after start time.`);
                timeInput_end.value = '';
            }
        }
    });

    // Event listener for end time
    timeInput_end.addEventListener('input', function() {
        var selectedTime = this.value;

        // Validate end time
        if (selectedTime < minTime || selectedTime > maxTime) {
            alert('Please select a time between 7:00 AM and 6:00 PM.');
            this.value = '';
            return;
        }

        // Validate against start time
        if (timeInput_start.value) {
            var startTimeMinutes = timeToMinutes(timeInput_start.value);
            var endTimeMinutes = timeToMinutes(selectedTime);
            
            if (endTimeMinutes <= startTimeMinutes) {
                alert('End time must be after start time.');
                this.value = '';
            } else if (endTimeMinutes - startTimeMinutes < minDifference) {
                alert(`End time must be at least ${minDifference} minutes after start time.`);
                this.value = '';
            }
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        var today = new Date().toISOString().split('T')[0];
        document.getElementById('edit_date').setAttribute('min', today);

        document.getElementById('edit_date').addEventListener('input', function() {
            var selectedDate = new Date(this.value);
            var dayOfWeek = selectedDate.getDay(); // 0=Sunday, 1=Monday, etc.

            // Check if selected date is a Sunday (dayOfWeek === 0) or a past date
            if (dayOfWeek === 0 || selectedDate < new Date(today)) {
                this.setCustomValidity('Please select a valid date (not a Sunday or in the past)');
            } else {
                this.setCustomValidity('');
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Get the date input element
        var dateInput = document.getElementById('edit_date');

        // Function to check if a given date is a Sunday
        function isSunday(date) {
            var dayOfWeek = date.getDay(); // 0 = Sunday, 1 = Monday, ..., 6 = Saturday
            return dayOfWeek === 0;
        }

        // Disable Sundays in the date picker
        dateInput.addEventListener('input', function() {
            var selectedDate = new Date(dateInput.value);
            if (isSunday(selectedDate)) {
                alert('Sundays are not allowed. Please choose another date.');
                dateInput.value = ''; // Clear the input value if it's a Sunday
            }
        });
    });
</script>
</html>