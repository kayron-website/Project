<?php
session_start();

if (isset($_SESSION['user_email'])) {
    $user_email = $_SESSION['user_email'];
    $allowed_roles = array("Admin");
    $user_type = $_SESSION['user_type'];

    if (!in_array($user_type, $allowed_roles)) {
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
	<link rel="stylesheet" href="css/button.css">
    <link rel="stylesheet" href="css/officials.css">
    <link href="/CAPSTONE/PAGES/css/fullcalendar.min.css" rel="stylesheet" />
	<script src="/CAPSTONE/PAGES/javascript/jquery.min.js"></script>
	<script src="/CAPSTONE/PAGES/javascript/moment.min.js"></script>
	<script src="/CAPSTONE/PAGES/javascript/fullcalendar.min.js"></script>
	<script src="/CAPSTONE/PAGES/javascript/bootstrap.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/calendar.css">
	<script src="javascript/fetch_department_members.js"></script>
    <link rel="stylesheet" href="practices/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        .fc-view-container {
            margin-top: 10px;
        }
        .fc-toolbar fc-header-toolbar {
            background: orange;
        }
    </style>
</head>
<body>
    <div class="web">
    <div class="wrapper">
        <nav class="nav">
            <div class="nav-logo" style="margin-top: 3px;">
                <p>SCHEDULER</p>
            </div>
            <div class="nav-menu" id="navMenu">
                <ul>
                    <li><a href="admin_calendar.php" class="link active">Calendar</a></li>
                    <li><div class="dropdown">
                        <a href="#" class="link" onclick="toggleDropdown()">Settings</a>
                        <div id="dropdownMenu" class="dropdown-content">
                            <a href="admin_register.php">Register</a>
                            <a href="admin_information.php">Information</a>
                            <a href="admin_promote.php">Promote</a>
                            <a href="admin_status.php">Status</a>
                            <a href="view_department.php">View Departments</a>
                        </div>
                    </div></li>
                    <li><a href="admin_dashboard.php" class="link">Dashboard</a></li>
                    <li><a href="logout.php" class="link" onclick="return confirm('Are you sure you want to logout?');">Logout</a></li>
                </ul>
            </div>
            <div class="nav-menu-btn">
                <i class="bx bx-menu" onclick="myMenuFunction()"></i>
            </div>
        </nav>
    </div>
            <div class="details_4">
                <div class="cards">
                    <div class="row">
                        <div class="col-lg-13">
                            <div id="calendar"></div>
                        </div>
                    </div>
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
                                    <p style="height: min-content;"><strong>Activity:</strong> <span id="activity_display" style="max-width: 215px;max-height: 180px;overflow-y: auto;border-radius: 10px;background: #ffeae0;"></span></p>
                                    <p><strong>Participants:</strong> <span id="user_emails_display" style="max-width: 292px;max-height: 180px;border-radius: 10px;background: #ffeae0;"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
function toggleDropdown() {
  var dropdownMenu = document.getElementById("dropdownMenu");
  if (dropdownMenu.style.display === "block") {
    dropdownMenu.style.display = "none";
  } else {
    dropdownMenu.style.display = "block";
  }
}
</script>
<script src="javascript/smtps.js"></script>
<script src="javascript/sidebar.js"></script>
<script src="https://smtpjs.com/v3/smtp.js"></script>
<script>
	$(document).ready(function() {
		display_events();
	});

	function display_events() {
		var events = new Array();

		$.ajax({
			url: 'display_event.php',
			dataType: 'json',
			success: function (response) {
				var result = response.data;
				$.each(result, function (i, item) {
					events.push({
                        event_id: result[i].event_id,
                        title: result[i].title,
                        start: result[i].start,
                        end: result[i].end,
                        color: result[i].color,
                        url: result[i].url,
                        activity: result[i].title,
                        date: moment(result[i].start).format('YYYY-MM-DD'),
                        start_time: result[i].start_time,
                        end_time: result[i].end_time,
                        location: result[i].location,
                        author: result[i].author,
                        user_emails: result[i].user_emails
                    });
				})

				var calendar = $('#calendar').fullCalendar({
					defaultView: 'month',
					timeZone: 'local',
					editable: true,
					selectable: true,
					selectHelper: true,
					select: function (start, end) {
						var currentDate = moment(); // Get the current date
						if (start.isBefore(currentDate) && !start.isSame(currentDate, 'day')) {
							return;
						}
						$('#date').val(moment(start).format('YYYY-MM-DD'));
						$('#end_date').val(moment(end).format('YYYY-MM-DD'));
						$('#event_entry_modal').modal('show');
					},
					events: events,
					header: {
						left: 'prev',
						center: 'title',
						right: 'next'
					},
                    eventClick: function(calEvent, jsEvent, view) {
                        $('#eventDetailsModal').modal('show');
                        $('#date_details').text(moment(calEvent.date).format('MMMM D YYYY')); // Formats date without ordinal indicator
                        $('#start_time_display').text(moment(calEvent.start_time, 'HH:mm').format('hh:mm A')); // Formats time in 12-hour format
                        $('#location_display').text(calEvent.location);
                        $('#activity_display').text(calEvent.activity);
                        $('#author_display').text(calEvent.author);
                        
                        // Display user emails associated with the event
                        var userEmails = calEvent.user_emails;
                        var userEmailsHtml = '';
                        if (userEmails && userEmails.length > 0) {
                            userEmailsHtml = userEmails.join(', '); // Join user emails with comma separator
                        } else {
                            userEmailsHtml = 'No user emails associated with this event.';
                        }
                        $('#user_emails_display').text(userEmailsHtml);
                    },
				});
			},
			error: function (xhr, status) {
				alert(response.msg);
			}
		});
	}
</script>
</html>