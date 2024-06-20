<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "scheduler";

session_start();

if (isset($_SESSION['user_email'])) {
    $user_email = $_SESSION['user_email'];
    $allowed_roles = array("Attendee");
    $user_type = $_SESSION['user_type'];

    if (!in_array($user_type, $allowed_roles)) {
        header("Location: unauthorized.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}

include('javascript/homepage.js');

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_email = $_SESSION['user_email']; // Retrieve the logged-in user's email

$sql_table_sched = "SELECT start_time, date, activity, location FROM calendar_event WHERE user_email = '$user_email'";
$result_table_sched = $conn->query($sql_table_sched);

// Query to fetch data from class_sched
$sql_class_sched = "SELECT start_time, date, subject, room
                    FROM class_sched 
                    WHERE user_email = '$user_email'";
$result_class_sched = $conn->query($sql_class_sched);
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
    <link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/profile.css">
	<link rel="stylesheet" href="css/notif.css">
	<link rel="stylesheet" href="css/button.css">
    <link rel="stylesheet" href="css/form_profile.css">
	<link rel="stylesheet" href="css/add_profile.css">
	<link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/boxicons.min.css">
    <link rel="stylesheet" href="css/clock.css">
    <script src='javascript/index.global.js'></script>
</head>
<body>
    <div class="web">
        <div class="navigation">
            <ul>
                <li>
                    <a href="">
                        <span class="title">CEIT</span>
                    </a>
                    <hr class="hr">
                </li>
                <li>
                    <a href="calendar_user.php">
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" height="30" width="30" viewBox="0 0 448 512" fill="#f2f2f2"><path d="M152 24c0-13.3-10.7-24-24-24s-24 10.7-24 24V64H64C28.7 64 0 92.7 0 128v16 48V448c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V192 144 128c0-35.3-28.7-64-64-64H344V24c0-13.3-10.7-24-24-24s-24 10.7-24 24V64H152V24zM48 192h80v56H48V192zm0 104h80v64H48V296zm128 0h96v64H176V296zm144 0h80v64H320V296zm80-48H320V192h80v56zm0 160v40c0 8.8-7.2 16-16 16H320V408h80zm-128 0v56H176V408h96zm-144 0v56H64c-8.8 0-16-7.2-16-16V408h80zM272 248H176V192h96v56z"/></svg>
                        </span>
                        <span class="title">Calendar</span>
                    </a>
                </li>
                <li>
                    <a class="sub-btn">
                        <span class="icon" id="settings">
                        <svg xmlns="http://www.w3.org/2000/svg" height="30" width="30" viewBox="0 0 512 512" fill="#f2f2f2"><path d="M495.9 166.6c3.2 8.7 .5 18.4-6.4 24.6l-43.3 39.4c1.1 8.3 1.7 16.8 1.7 25.4s-.6 17.1-1.7 25.4l43.3 39.4c6.9 6.2 9.6 15.9 6.4 24.6c-4.4 11.9-9.7 23.3-15.8 34.3l-4.7 8.1c-6.6 11-14 21.4-22.1 31.2c-5.9 7.2-15.7 9.6-24.5 6.8l-55.7-17.7c-13.4 10.3-28.2 18.9-44 25.4l-12.5 57.1c-2 9.1-9 16.3-18.2 17.8c-13.8 2.3-28 3.5-42.5 3.5s-28.7-1.2-42.5-3.5c-9.2-1.5-16.2-8.7-18.2-17.8l-12.5-57.1c-15.8-6.5-30.6-15.1-44-25.4L83.1 425.9c-8.8 2.8-18.6 .3-24.5-6.8c-8.1-9.8-15.5-20.2-22.1-31.2l-4.7-8.1c-6.1-11-11.4-22.4-15.8-34.3c-3.2-8.7-.5-18.4 6.4-24.6l43.3-39.4C64.6 273.1 64 264.6 64 256s.6-17.1 1.7-25.4L22.4 191.2c-6.9-6.2-9.6-15.9-6.4-24.6c4.4-11.9 9.7-23.3 15.8-34.3l4.7-8.1c6.6-11 14-21.4 22.1-31.2c5.9-7.2 15.7-9.6 24.5-6.8l55.7 17.7c13.4-10.3 28.2-18.9 44-25.4l12.5-57.1c2-9.1 9-16.3 18.2-17.8C227.3 1.2 241.5 0 256 0s28.7 1.2 42.5 3.5c9.2 1.5 16.2 8.7 18.2 17.8l12.5 57.1c15.8 6.5 30.6 15.1 44 25.4l55.7-17.7c8.8-2.8 18.6-.3 24.5 6.8c8.1 9.8 15.5 20.2 22.1 31.2l4.7 8.1c6.1 11 11.4 22.4 15.8 34.3zM256 336a80 80 0 1 0 0-160 80 80 0 1 0 0 160z"/></svg>
                        </span>
                        <span class="title">Settings</span>
                        <svg class="dropdown" xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 256 512"><path d="M246.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-9.2-9.2-22.9-11.9-34.9-6.9s-19.8 16.6-19.8 29.6l0 256c0 12.9 7.8 24.6 19.8 29.6s25.7 2.2 34.9-6.9l128-128z" style="fill: #899DC1;"/></svg>
                    </a>
                    <div class="sub-menu">
                        <a href="information_user.php" class="sub-item">
                            <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 512 512" fill="#f2f2f2"><path d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z"/></svg>
                            <div class="text">Information</div>
                        </a>
                    </div>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
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
                    <a href="profile_user.php">
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" height="30" width="30" viewBox="0 0 512 512" fill="#f2f2f2"><path d="M399 384.2C376.9 345.8 335.4 320 288 320H224c-47.4 0-88.9 25.8-111 64.2c35.2 39.2 86.2 63.8 143 63.8s107.8-24.7 143-63.8zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm256 16a72 72 0 1 0 0-144 72 72 0 1 0 0 144z"/></svg>
                        </span>
                        <span class="title">Profile</span>
                    </a>
                </li>
                <li>
                    <a href="homepage_user.php" class="active">
                        <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" height="30" width="30" viewBox="0 -960 960 960" fill="#FC6A03"><path d="M520-600v-240h320v240H520ZM120-440v-400h320v400H120Zm400 320v-400h320v400H520Zm-400 0v-240h320v240H120Zm80-400h160v-240H200v240Zm400 320h160v-240H600v240Zm0-480h160v-80H600v80ZM200-200h160v-80H200v80Zm160-320Zm240-160Zm0 240ZM360-280Z"/></svg>
                        </span>
                        <span class="title" style="color: #FC6A03;">Dashboard</span>
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
                    <a href="about_user.php" class="bottom">
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" height="30" width="30" viewBox="0 0 512 512" fill="#f2f2f2"><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM216 336h24V272H216c-13.3 0-24-10.7-24-24s10.7-24 24-24h48c13.3 0 24 10.7 24 24v88h8c13.3 0 24 10.7 24 24s-10.7 24-24 24H216c-13.3 0-24-10.7-24-24s10.7-24 24-24zm40-208a32 32 0 1 1 0 64 32 32 0 1 1 0-64z"/></svg>
                        </span>
                        <span class="title">About Us</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="main">
            <div class="topbar">
                <div class="toggle">
                  <svg xmlns="http://www.w3.org/2000/svg" height="25" width="25" viewBox="0 0 448 512"><path d="M0 96C0 78.3 14.3 64 32 64H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 128 0 113.7 0 96zM0 256c0-17.7 14.3-32 32-32H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32c-17.7 0-32-14.3-32-32zM448 416c0 17.7-14.3 32-32 32H32c-17.7 0-32-14.3-32-32s14.3-32 32-32H416c17.7 0 32 14.3 32 32z"/></svg>
                </div>
                <div class="card_8">
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
                        $query = "SELECT COUNT(*) AS schedule_count FROM calendar_event WHERE user_email = '$user_email'";
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
                                        $query = "SELECT activity, start_time, date FROM calendar_event WHERE user_email = '$user_email'";
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
            <div class="page-header">
                <h1>Dashboard</h1>
                <small>Home / Dashboard</small>
            </div>
            <div class="cardBox_2">
                    <div class="card">
                        <div class="cards">
                            <div class="card-head">
                                <h2><?php echo $total_ongoing; ?></h2>
                                <span><svg xmlns="http://www.w3.org/2000/svg" height="50" width="60" viewBox="0 0 512 512"><path d="M256 0a256 256 0 1 1 0 512A256 256 0 1 1 256 0zM232 120V256c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2V120c0-13.3-10.7-24-24-24s-24 10.7-24 24z" style="fill: rgb(190, 184, 184);"/></svg>
                                </span>
                            </div>
                            <div class="card-progress">
                                <small>Current Schedules</small>
                                <div class="card-indicator">
                                    <div class="indicator one" style="width:<?php echo ($total_ongoing * 10); ?>%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="cards">
                            <div class="card-head">
                                <h2><?php echo $total_pending; ?></h2>
                                <span><svg xmlns="http://www.w3.org/2000/svg" height="50" width="60" viewBox="0 0 512 512"><path d="M40 48C26.7 48 16 58.7 16 72v48c0 13.3 10.7 24 24 24H88c13.3 0 24-10.7 24-24V72c0-13.3-10.7-24-24-24H40zM192 64c-17.7 0-32 14.3-32 32s14.3 32 32 32H480c17.7 0 32-14.3 32-32s-14.3-32-32-32H192zm0 160c-17.7 0-32 14.3-32 32s14.3 32 32 32H480c17.7 0 32-14.3 32-32s-14.3-32-32-32H192zm0 160c-17.7 0-32 14.3-32 32s14.3 32 32 32H480c17.7 0 32-14.3 32-32s-14.3-32-32-32H192zM16 232v48c0 13.3 10.7 24 24 24H88c13.3 0 24-10.7 24-24V232c0-13.3-10.7-24-24-24H40c-13.3 0-24 10.7-24 24zM40 368c-13.3 0-24 10.7-24 24v48c0 13.3 10.7 24 24 24H88c13.3 0 24-10.7 24-24V392c0-13.3-10.7-24-24-24H40z" style="fill: rgb(190, 184, 184);"/></svg>
                                </span>
                            </div>
                            <div class="card-progress">
                                <small>Incoming Schedules</small>
                                <div class="card-indicator">
                                    <div class="indicator two" style="width:<?php echo ($total_pending * 10); ?>%"></div>
                                </div>
                            </div>
                        </div>   
                    </div>
            </div>
                    <div class="details">
                        <div class="card_sched">
                            <div class="records table-responsive" id="table2">
                                <div class="table-container">
                                    <table class="table" width="100%">
                                        <caption>My Work Schedules</caption>
                                        <thead>
                                            <tr>
                                                <th>TIME</th>
                                                <th>DATE</th>
                                                <th>ACTIVITY/SUBJECT</th>
                                                <th>PLACE</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                if ($result_table_sched->num_rows > 0) {
                                                    while ($row_table_sched = $result_table_sched->fetch_assoc()) {
                                                        echo "<tr>";
                                                        $start_time_12hour = date("h:i A", strtotime($row_table_sched["start_time"]));
                                                        echo "<td><h4>" . $start_time_12hour . "</h4></td>";
                                                        $formatted_date = date("F j, Y", strtotime($row_table_sched["date"]));
                                                        echo "<td><h4>" . $formatted_date . "</h4></td>";
                                                        echo "<td><h4>" . $row_table_sched["activity"] . "</h4></td>";
                                                        echo "<td><h4>" . $row_table_sched["location"] . "</h4></td>";
                                                        echo "</tr>";
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='4'>No Calendar Schedule</td></tr>";
                                                }
                                            ?>    
                                            <?php
                                                if ($result_class_sched->num_rows > 0) {
                                                    while ($row_class_sched = $result_class_sched->fetch_assoc()) {
                                                        echo "<tr>";
                                                        $start_time_12hour = date("h:i A", strtotime($row_class_sched["start_time"]));
                                                        echo "<td><h4>" . $start_time_12hour . "</h4></td>";
                                                        $formatted_date = date("F j, Y", strtotime($row_class_sched["date"]));
                                                        echo "<td><h4>" . $formatted_date . "</h4></td>";
                                                        echo "<td><h4>" . $row_class_sched["subject"] . "</h4></td>";
                                                        echo "<td><h4>" . $row_class_sched["room"] . "</h4></td>";
                                                        echo "</tr>";
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='4'>No Class Schedule</td></tr>";
                                                }
                                            ?>                                                     
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
        </div>
    </div>
</body>
<script src="javascript/sidebar.js"></script>
</html>