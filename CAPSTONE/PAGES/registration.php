<?php
@include 'config.php';

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

// Fetch departments from the database
$departments = array();
$sql = "SELECT department_name FROM departments";
$result = mysqli_query($conn, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $departments[] = $row['department_name'];
    }
} else {
    echo "Error: " . mysqli_error($conn);
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
    <link rel="stylesheet" href="practices/style.css">
    <script>
        function selectDepartment() {
            var departmentSelect = document.getElementById("department");
            var selectedDepartment = departmentSelect.options[departmentSelect.selectedIndex].value;
            var errorSpan = document.getElementById("departmentError");

            if (selectedDepartment === "") {
                errorSpan.textContent = 'Please choose your department.';
            } else {
                errorSpan.textContent = '';
            }
        }

        function selectUser_type() {
            var user_typeSelect = document.getElementById("user_type");
            var selectedUser_type = user_typeSelect.options[user_typeSelect.selectedIndex].value;
            var errorSpan = document.getElementById("user_typeError");

            if (selectedUser_type === "") {
                errorSpan.textContent = 'Please Choose Position for the user.';
            } else {
                errorSpan.textContent = '';
            }
        }
    </script>
</head>
<body>
    <div class="web">
        <div class="main">
            <div class="wrapper">
                <nav class="nav">
                    <div class="nav-logo" style="margin-top: 3px;">
                        <p>SCHEDULER</p>
                    </div>
                    <div class="nav-menu" id="navMenu">
                        <ul>
                            <li><a href="#" class="link">Calendar</a></li>
                            <li><div class="dropdown">
                                <a href="#" class="link active" onclick="toggleDropdown()">Settings</a>
                                <div id="dropdownMenu" class="dropdown-content">
                                    <a href="#">Register</a>
                                    <a href="#">Information</a>
                                    <a href="#">Promote</a>
                                    <a href="#">Status</a>
                                    <a href="#">Departments</a>
                                </div>
                            </div></li>
                            <li><a href="#" class="link">Dashboard</a></li>
                            <li><a href="#" class="link">Logout</a></li>
                        </ul>
                    </div>
                    <div class="nav-menu-btn">
                        <i class="bx bx-menu" onclick="myMenuFunction()"></i>
                    </div>
                </nav>
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
            </div>
            <div class="details_2">
                <h1 style="text-align: center;">Register Account</h1>
                <form class="form" action="send_email.php" method="post">
                    <?php
                        if(isset($register_error)){
                            echo '<span class="error-msg">'.$register_error.'</span>';
                        };
                    ?>
                    <label>
                        <input required="" type="text" id="name" name="name" class="input">
                        <span>Name</span>
                    </label>
                    <label>
                        <input required="" type="email" id="recipient_email" name="recipient_email" class="input">
                        <span>Email</span>
                    </label>
                    <label>
                    <input required="" type="password" id="password" name="password" class="input">
                        <span>Password</span>
                    </label>
                    <label>
                        <input required="" type="password" name="cpassword" class="input">
                        <span>Confirm Password</span>
                    </label>
                    <label>
                        <input required="" type="text" id="position" name="position" class="input">
                        <span>Position</span>
                    </label>
                    <div class="flex">
                        <label>
                            <select class="input" id="user_type" id="typeofuser" name="user_type" onchange="selectUser_type()">
                                <option value="">Type of User</option>
                                <option value="Admin">Admin</option>
                                <option value="Implementor">Implementor</option>
                                <option value="Attendee">Attendee</option>
                            </select>
                        </label>
                        <label>
                            <select class="input" id="department" name="department" onchange="selectDepartment()">
                                <option value="">Department</option>
                                <?php foreach ($departments as $department): ?>
                                    <option value="<?php echo htmlspecialchars($department); ?>"><?php echo htmlspecialchars($department); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                    </div>         
                    <span id="departmentError" class="error-msg"></span>
                    <span id="user_typeError" class="error-msg"></span>  
                    <input type="submit" name="register" value="Send Email" onclick="submitForm()">
                </form>  
            </div>
        </div>
    </div>
    <script>
        function submitForm() {
            var name = document.getElementById("name").value;
            var email = document.getElementById("recipient_email").value;
            var password = document.getElementById("password").value;
            var position = document.getElementById("position").value;
            var userType = document.getElementById("user_type").value;
            var department = document.getElementById("department").value;

            // Prepare data to send via AJAX
            var formData = {
                name: name,
                user_email: email,
                password: password,
                position: position,
                user_type: userType,
                department: department
            };

            $.ajax({
                type: "POST",
                url: "save_to_database.php", // Replace with your PHP file for database insertion
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        sendEmail(); // Call the sendEmail function after successful database insertion
                    } else {
                        console.error("Failed to save data to the database");
                        // Handle error if data couldn't be saved to the database
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    // Handle error if AJAX request fails
                }
            });

            return false; // Prevent the default form submission
        }
    </script>
</body>
<script src="javascript/sidebar.js"></script>
</html>
