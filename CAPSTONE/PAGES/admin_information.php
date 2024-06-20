<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "scheduler";

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

include('javascript/homepage.js');

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userEmail = $_SESSION["user_email"];
$userQuery = "SELECT * FROM user_form WHERE user_email = '$userEmail'";
$result = $conn->query($userQuery);

if (!$result) {
    exit("Database error");
}

$user = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['update_name'])) {
        $newName = $_POST['new_name'];
        $updateNameQuery = "UPDATE user_form SET name = '$newName' WHERE user_email = '$userEmail'";
        $conn->query($updateNameQuery);
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    }
    if (isset($_POST['update_nickname'])) {
        $newNickname = $_POST['new_nickname'];
        $updateNicknameQuery = "UPDATE user_form SET nickname = '$newNickname' WHERE user_email = '$userEmail'";
        $conn->query($updateNicknameQuery);
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    }
    if (isset($_POST['update_password'])) {
      $newPassword = $_POST['new_password'];
      $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
      $updatePasswordQuery = "UPDATE user_form SET password = '$hashedPassword' WHERE user_email = '$userEmail'";
      $conn->query($updatePasswordQuery);
      header("Location: {$_SERVER['PHP_SELF']}");
      exit();
  }
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
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="practices/style.css">
    <style>
        .container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh; /* This centers the container vertically */
    background-color: #f2f2f2; /* Dark background color */
}

#customers {
    border-radius: 10px;
    height: 100%;
    border-collapse: collapse;
    width: 100%;
    max-width: 1000px;
    max-height: 500px;
    color: #2a2a2a;
}

#customers th, #customers td {
    border: 1px solid #555; /* Border color */
    padding: 8px;
}

#customers th {
    background-color: white; /* Header background color */
}

#customers tr:nth-child(even) {
    background-color: #f2f2f2; /* Even row background color */
}

#customers tr:nth-child(odd) {
    background-color: white; /* Odd row background color */
}

#customers th, #customers td {
    text-align: left;
}

.edit-form {
    display: none;
}

.status {
    background-color: #FC6A03; /* Green */
    border: none;
    color: white;
    padding: 5px 10px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 14px;
    margin: 4px 2px;
    cursor: pointer;
    border-radius: 4px;
}
    </style>
</head>
<body>
    <script>
        function showEditForm(formId) {
            document.getElementById(formId).style.display = 'block';
        }

        function maskPassword() {
            var passwordContainer = document.getElementById("passwordContainer");
            var passwordValue = passwordContainer.textContent;
            var truncatedPassword = passwordValue.substring(0, 4);
            passwordContainer.innerHTML = '<input type="password" value="' + truncatedPassword + '" disabled>';
        }
    </script>
    <div class="web">
        <div class="wrapper">
            <nav class="nav">
                <div class="nav-logo" style="margin-top: 3px;">
                    <p>SCHEDULER</p>
                </div>
                <div class="nav-menu" id="navMenu">
                    <ul>
                        <li><a href="admin_calendar.php" class="link">Calendar</a></li>
                        <li><div class="dropdown">
                            <a href="#" class="link active" onclick="toggleDropdown()">Settings</a>
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
        <div class="container">
            <table id='customers'>
                    <tr>
                        <th>Attribute</th>
                        <th>Value</th>
                        <th>Customize</th>
                    </tr>
                    <tr>
                        <td>Full Name</td>
                        <td>
                            <div id="nameContainer">
                                <?php echo $user["name"]; ?>
                            </div>
                            <form id="nameForm" class="edit-form" method="POST">
                                <input type="text" name="new_name" placeholder="New name">
                                <button class='status' type="submit" name="update_name">Save</button>
                            </form>
                        </td>
                        <td>
                            <button class='status' onclick="showEditForm('nameForm')">Edit</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Name</td>
                        <td>
                            <div id="nicknameContainer">
                                <?php echo $user["nickname"]; ?>
                            </div>
                            <form id="nicknameForm" class="edit-form" method="POST">
                                <input type="text" name="new_nickname" placeholder="New nickname">
                                <button class='status' type="submit" name="update_nickname">Save</button>
                            </form>
                        </td>
                        <td>
                            <button class='status' onclick="showEditForm('nicknameForm')">Edit</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td><?php echo $user["user_email"]; ?></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Password</td>
                        <td>
                            <div id="passwordContainer">
                                <?php
                                    $maskedPassword = str_repeat('●', min(strlen($user["password"]), 4));
                                    echo $maskedPassword;
                                ?>
                            </div>
                            <form id="passwordForm" class="edit-form" method="POST">
                                <input type="password" name="new_password" placeholder="New password">
                                <button class='status' type="submit" name="update_password">Save</button>
                            </form>
                        </td>
                        <td>
                            <button class='status' onclick="showEditForm('passwordForm'); maskPassword();">Change</button>
                        </td>
                    </tr>
                    <tr>
                        <td>User Type</td>
                        <td><?php echo $user["user_type"]; ?></td>
                        <td></td>
                    </tr>
                </table>
        </div>
    </div>
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
</body>
</html>