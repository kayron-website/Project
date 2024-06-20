<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "scheduler";

$conn = new mysqli($servername, $username, $password, $dbname);

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

$userEmail = $_SESSION["user_email"];

$userQuery = "SELECT * FROM user_form WHERE user_email = '$userEmail'";
$result = mysqli_query($conn, $userQuery);

if (!$result) {
    exit("Database error");
}

$user = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["image"]["name"])) {
    $id = $user["id"];
    $name = $user["name"];
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
    <link rel="stylesheet" href="css/practice.css">
    <link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/profile.css">
	<link rel="stylesheet" href="css/notif.css">
	<link rel="stylesheet" href="css/button.css">
    <!-- <link rel="stylesheet" href="css/form_profile.css"> -->
	<link rel="stylesheet" href="css/add_profile.css">
	<link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/boxicons.min.css">
    <link rel="stylesheet" href="css/clock.css">
    <script src='javascript/index.global.js'></script>
    <link rel="stylesheet" href="javascript/profile_page.css">
    <script src='javascript/main.min.js'></script>
    <script src='javascript/main.mins.js'></script>
    <script src='javascript/main.minss.js'></script>
    <script src='javascript/main.minsss.js'></script>
    <style>
        #calendar {
            margin-bottom: 20px;
            height: 670px;
        }
        .create-event-form {
            display: block;
        }
        .edit-event-form {
            display: none;
        }
        .delete-event-button {
            margin-top: 10px;
            cursor: pointer;
            color: red;
        }
        
    </style>
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
                    <a href="profile_user.php" class="active">
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" height="30" width="30" viewBox="0 0 512 512" fill="#FC6A03"><path d="M399 384.2C376.9 345.8 335.4 320 288 320H224c-47.4 0-88.9 25.8-111 64.2c35.2 39.2 86.2 63.8 143 63.8s107.8-24.7 143-63.8zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm256 16a72 72 0 1 0 0-144 72 72 0 1 0 0 144z"/></svg>
                        </span>
                        <span class="title" style="color: #FC6A03;">Profile</span>
                    </a>
                </li>
                <li>
                    <a href="homepage_user.php">
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
            <div class="cardBox">
                    <div class="card">
                        <div class="background">
                            <div class="profile">
                                <form class="form" id="form" action="" enctype="multipart/form-data" method="post">
                                    <div class="upload">
                                        <?php
                                            $id = $user["id"];
                                            $name = $user["name"];
                                            $image = $user["image"];
                                        ?>
                                        <?php if (!empty($image)) : ?>
                                            <img src="img/<?php echo $image; ?>" width="200" height="200">
                                        <?php else : ?>
                                            <img src="img/img.png" width="200" height="200"> <!-- Default Image -->
                                        <?php endif; ?>
                                        <div class="round">
                                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                                            <input type="hidden" name="name" value="<?php echo $name; ?>">
                                            <input type="file" name="image" id="image" accept=".jpg, .jpeg, .png">
                                            <div class="pick">
                                                <svg xmlns="http://www.w3.org/2000/svg" height="20" width="20" viewBox="0 0 512 512">
                                                    <path d="M149.1 64.8L138.7 96H64C28.7 96 0 124.7 0 160V416c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V160c0-35.3-28.7-64-64-64H373.3L362.9 64.8C356.4 45.2 338.1 32 317.4 32H194.6c-20.7 0-39 13.2-45.5 32.8zM256 192a96 96 0 1 1 0 192 96 96 0 1 1 0-192z" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <script type="text/javascript">
                                    document.getElementById("image").onchange = function() {
                                        document.getElementById("form").submit();
                                    };
                                </script>
                                <?php
                                    if (isset($_FILES["image"]["name"])) {
                                        $id = $_POST["id"];
                                        $name = $_POST["name"];

                                        $imageName = $_FILES["image"]["name"];
                                        $imageSize = $_FILES["image"]["size"];
                                        $tmpName = $_FILES["image"]["tmp_name"];

                                        $validImageExtension = ['jpg', 'jpeg', 'png'];
                                        $imageExtension = explode('.', $imageName);
                                        $imageExtension = strtolower(end($imageExtension));

                                        if (!in_array($imageExtension, $validImageExtension)) {
                                            echo "<script>alert('Invalid Image Extension');</script>";
                                        } elseif ($imageSize > 1200000) {
                                            echo "<script>alert('Image Size Is Too Large');</script>";
                                        } else {
                                            $newImageName = $name . '.' . $imageExtension;
                                            $query = "UPDATE user_form SET image = '$newImageName' WHERE id = $id";
                                            mysqli_query($conn, $query);
                                            move_uploaded_file($tmpName, 'img/' . $newImageName);
                                            echo "<script>window.location.href = 'profile_user.php';</script>";
                                        }
                                    }
                                ?>
                            </div>	
                        </div>
                    </div>
                    <div class="card">
                        <div class="profile-container">
                            <div class="profile-info">
                                <?php
                                    if (!empty($_SESSION['user_email'])) {
                                        $userEmail = $_SESSION['user_email'];

                                        $sql = "SELECT name, user_email, position FROM user_form WHERE user_email = '$userEmail'";
                                        $result = $conn->query($sql);

                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $name = $row["name"];
                                                $email = $row["user_email"];
                                                $position = $row["position"];
                                                echo '
                                                    <div class="info-item">
                                                        <div class="info-label">Name:</div>
                                                        <div class="card_1">
                                                            <div class="info-value_1">' . $name . '</div>
                                                        </div>
                                                    </div>
                                                    <hr class="line">
                                                    <div class="info-item">
                                                        <div class="info-label">Email:</div>
                                                        <div class="card_2">
                                                            <div class="info-value_2">' . $email . '</div>
                                                        </div>
                                                    </div>
                                                    <hr class="line">
                                                    <div class="info-item">
                                                        <div class="info-label">Position:</div>
                                                        <div class="card_5">
                                                            <div class="info-value_5">' . $position . '</div>
                                                        </div>
                                                    </div>
                                                    <hr class="line">
                                                ';
                                            }
                                        } else {
                                            echo "No results found";
                                        }
                                    } else {
                                        echo "User email not found in session";
                                    }
                                ?>
                                <hr class="line">
                                <div class="info-item">
                                    <div class="info-label">Department:</div>
                                    <div class="card_4">
                                        <div class="info-value_4">
                                        <?php
                                            // Check the department code and display the appropriate label
                                            $departmentCode = $_SESSION['user_department'];

                                            if ($departmentCode === 'DIT') {
                                                echo 'Department of Information Technology';
                                            } elseif ($departmentCode === 'DIET') {
                                                echo 'Department of Industrial Engineering and Technology';
                                            } elseif ($departmentCode === 'DCEE') {
                                                echo 'Department of Computer and Electronics Engineering';
                                            } elseif ($departmentCode === 'DCEA') {
                                                echo 'Department of Civil Engineering and Architecture';
                                            } elseif ($departmentCode === 'DAFE') {
                                                echo 'Department of Agricultural and Food Engineering';
                                            } else {
                                                echo $departmentCode; // Display the department code for other cases
                                            }
                                        ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="backg"></div>
                        <div class="clock_container">
                            <div class="clock__content grid">
                                <div class="clock__text">
                                    <div class="clock__text-hour" id="text-hours"></div>
                                    <div class="clock__text-minutes" id="text-minutess"></div>
                                    <div class="clock__text-ampm" id="text-ampms"></div>
                                </div>
                                <div class="clock__date">
                                    <span id="date-days"></span>
                                    <span id="date-months"></span>
                                    <span id="date-years"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script src="javascript/clocks.js"></script>
            </div>
            <center><h3 class="headers mt-5" style="margin-top: 0px;margin-bottom: 5px;background: #2a2a2a;color: #f2f2f2;">Class Schedule Calendar</h3></center>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createEventModal" style="margin-bottom: 5px;">Create Event</button>
            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#editEventModal" style="margin-bottom: 5px;">Edit Event</button>
            <!-- THis is for the class schedule -->
            <div class="container">
            
            <!-- Create Event Button -->
            <!-- Create Event Button -->
            

                <!-- Create Event Modal -->
                <div class="modal fade mt-5" id="createEventModal" tabindex="-1" role="dialog" aria-labelledby="createEventModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="createEventModalLabel">Create Event</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form class="create-event-form" action="class_schedule.php" method="post">
                                    <div class="form-group">
                                        <label for="date">Start Month:</label>
                                        <input type="date" class="form-control" name="date" id="date" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="end_date">End Month:</label>
                                        <input type="date" class="form-control" name="end_date" id="end_date" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="start_time">Start Time:</label>
                                        <input type="time" class="form-control" name="start_time" id="start_time" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="end_time">End Time:</label>
                                        <input type="time" class="form-control" name="end_time" id="end_time" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="title">Subject:</label>
                                        <input type="text" class="form-control" name="title" id="title" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="place">Room No:</label>
                                        <input type="text" class="form-control" name="place" id="place" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save Schedule</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Event Button (for demonstration purposes, this would normally be generated dynamically) -->
                

                <!-- Edit Event Modal -->
                <div class="modal fade mt-5" id="editEventModal" tabindex="-1" role="dialog" aria-labelledby="editEventModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editEventModalLabel">Edit Event</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form class="edit-event-form" action="class_edit_event.php" method="post">
                                    <input type="hidden" name="event_id" id="editEventId">
                                    <div class="form-group">
                                        <label for="editDate">Date:</label>
                                        <input type="date" class="form-control" name="editDate" id="editDate" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="editTime">Time:</label>
                                        <input type="text" class="form-control" name="editTime" id="editTime" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="editTitle">Subject:</label>
                                        <input type="text" class="form-control" name="editTitle" id="editTitle" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="editPlace">Room No.:</label>
                                        <input type="text" class="form-control" name="editPlace" id="editPlace" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                    <button type="button" id="deleteEventButton" class="btn btn-danger">Delete Event</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
            <div id='calendar'></div>
        </div>
    </div>
</body>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        editable: true,
        selectable: true,
        events: 'class_events.php', // Load events from the PHP script
        headerToolbar: {
            left: 'prev',
            center: 'title',
            right: 'next'
        },
        slotMinTime: '07:00:00', // Set the start time to 7 AM
        slotMaxTime: '19:00:00', // Set the end time to 6 PM
        eventClick: function(info) {
    console.log('Event clicked:', info);

    // Show the edit event form
    document.querySelector('.create-event-form').style.display = 'none';
    document.querySelector('.edit-event-form').style.display = 'block';

    // Populate edit form fields with event details
    document.getElementById('editEventId').value = info.event.id;
    document.getElementById('editDate').value = info.event.startStr.slice(0, 10);
    document.getElementById('editTime').value = info.event.startStr.slice(11, 16) + '-' + info.event.endStr.slice(11, 16);
    document.getElementById('editTitle').value = info.event.title;
    document.getElementById('editPlace').value = info.event.extendedProps.place;

    // Remove any existing event listeners to avoid multiple bindings
    var deleteButton = document.getElementById('deleteEventButton');
    var newButton = deleteButton.cloneNode(true);
    deleteButton.parentNode.replaceChild(newButton, deleteButton);

    // Add event listener for delete button
    newButton.addEventListener('click', function() {
        if (confirm('Are you sure you want to delete this event?')) {
            // Send AJAX request to delete event
            var eventId = info.event.id;
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Event deleted successfully
                        alert(xhr.responseText);
                        calendar.refetchEvents();
                        document.querySelector('.create-event-form').style.display = 'block';
                        document.querySelector('.edit-event-form').style.display = 'none';
                    } else {
                        // Error occurred
                        alert('Error: ' + xhr.responseText);
                    }
                }
            };
            xhr.open('POST', 'class_delete_event.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send('event_id=' + eventId);
        }
    });
}
    });

    // Render the calendar
    calendar.render();

    $('#createEventModal').on('shown.bs.modal hidden.bs.modal', function () {
        calendar.updateSize();
    });

    $('#editEventModal').on('shown.bs.modal hidden.bs.modal', function () {
        calendar.updateSize();
    });

    // Add validation to forms
    document.querySelector('.create-event-form').addEventListener('submit', function(event) {
        if (!validateTime(document.getElementById('time').value)) {
            event.preventDefault();
        }
    });

    document.querySelector('.edit-event-form').addEventListener('submit', function(event) {
        if (!validateTime(document.getElementById('editTime').value)) {
            event.preventDefault();
        }
    });
});

// Get the input elements
var dateInput = document.getElementById('date');
var endDateInput = document.getElementById('end_date');
var timeInput_start = document.getElementById('start_time');
var timeInput_end = document.getElementById('end_time');

// Set minimum and maximum time
var minTime = '07:00'; // 7:00 AM
var maxTime = '18:00'; // 6:00 PM

// Minimum difference in minutes
var minDifference = 20; // 20 minutes
var minMonthDifference = 30 * 24 * 60; // 1 month in minutes

// Function to convert date string to Date object
function parseDate(dateStr) {
    var parts = dateStr.split('-');
    return new Date(parts[0], parts[1] - 1, parts[2]); // month is 0-based
}

// Function to convert time string to minutes since midnight
function timeToMinutes(time) {
    var [hours, minutes] = time.split(':').map(Number);
    return hours * 60 + minutes;
}

// Function to calculate difference in minutes between two dates
function dateDifferenceInMinutes(date1, date2) {
    return Math.abs(date2.getTime() - date1.getTime()) / (1000 * 60);
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
        var startTimeMinutes = timeToMinutes(selectedTime);
        var endTimeMinutes = timeToMinutes(timeInput_end.value);
        
        if (endTimeMinutes <= startTimeMinutes) {
            alert('End time must be after start time.');
            timeInput_end.value = '';
        } else if (endTimeMinutes - startTimeMinutes < minDifference) {
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

// Event listener for end date
endDateInput.addEventListener('change', function() {
    var startDate = parseDate(dateInput.value);
    var endDate = parseDate(endDateInput.value);

    var differenceInMinutes = dateDifferenceInMinutes(startDate, endDate);
    
    if (differenceInMinutes < minMonthDifference) {
        alert('End date must be at least 1 month after the start date.');
        this.value = '';
    }
});
</script>

    <script src="javascript/jquery-3.5.1.slim.min.js"></script>
    <script src="javascript/popper.min.js"></script>
    <script src="javascript/bootstrap.min.js"></script>
    <script src="javascript/sidebar.js"></script>
</html>