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
function getDepartments($conn) {
    $departments = array();
    $query = "SELECT name FROM departments"; // Assuming the department table has a column named department_name
    $result = mysqli_query($conn, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $departments[] = $row['name'];
        }
    }

    return $departments;
}

$departments = getDepartments($conn);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scheduler</title>
    <link rel="icon" type="image/x-icon" href="img/logo.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/font_1.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="practices/style.css">
    <script src="javascript/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
    <div class="form-box">
        <form class="form" action="send_email.php" method="post">
            <?php
                if(isset($register_error)){
                    echo '<span class="error-msg">'.$register_error.'</span>';
                };
            ?>
            <div class="login-container" id="login">
                <div class="top">
                    <header>Register Account</header>
                </div>
                <div class="input-box">
                    <input required="" type="text" id="name" name="name" class="input-field" placeholder="Full Name">
                    <i class="bx bx-user"></i>
                </div>
                <div class="input-box">
                    <input required="" type="email" id="recipient_email" name="recipient_email" class="input-field" placeholder="Email">
                    <i class="bx bx-envelope"></i>
                </div>
                <div class="input-box">
                    <input required="" type="password" id="password" name="password" class="input-field" placeholder="Password">
                    <button id="togglePassword" type="button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="transparent" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather-eye-off" style="position: absolute;margin-top: -50px;margin-left: 460px;">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                            <line x1="1" y1="1" x2="23" y2="23"></line>
                        </svg>
                    </button>
                </div>
                <label>
                    <div class="select-wrapper">
                        <select required="" class="input" id="position_type" name="position_type" onchange="selectPosition()">
                            <option value="">Position</option>
                            <option value="DEAN">DEAN</option>
                            <option value="Chairperson">Chairperson</option>
                            <option value="College Secretary">College Secretary</option>
                            <option value="College Budget Officer">College Budget Officer</option>
                            <option value="College Registrar">College Registrar</option>
                            <option value="Assistant College Registrar">Assistant College Registrar</option>
                            <option value="College MIS/PIO Officer">College MIS/PIO Officer</option>
                            <option value="Coordinator Research Services, Coordinator Graduate Programs">Coordinator Research Services, Coordinator Graduate Programs</option>
                            <option value="Officials">Coordinator, Extension Services</option>
                            <option value="Coordinator, R&E Monitoring and Evaluation Unit">Coordinator, R&E Monitoring and Evaluation Unit</option>
                            <option value="College OJT Coordinator">College OJT Coordinator</option>
                            <option value="Coordinator, College Quality Assurance and Accreditation">Coordinator, College Quality Assurance and Accreditation</option>
                            <option value="Asst. Coordinator, College Quality Assurance and Accreditation<">Asst. Coordinator, College Quality Assurance and Accreditation</option>
                            <option value="Coordinator, Knowledge Management Unit">Coordinator, Knowledge Management Unit</option>
                            <option value="Coordinator, Gender and Development Program">Coordinator, Gender and Development Program</option>
                            <option value="Coordinator, Gender and Development Program (alternate)">Coordinator, Gender and Development Program (alternate)</option>
                            <option value="Coordinator for Sports and Socio-Cultural Development">Coordinator for Sports and Socio-Cultural Development</option>
                            <option value="College Review Coordinator for BSABE and BSCE">College Review Coordinator for BSABE and BSCE</option>
                            <option value="College Review Coordinator for BSECE and BSEE">College Review Coordinator for BSECE and BSEE</option>
                            <option value="College Guidance Counselor for BSABE, BSIT, BSCS, and Architecture">College Guidance Counselor for BSABE, BSIT, BSCS, and Architecture</option>
                            <option value="College Guidance Counselor for BSCE, BSECE, BSEE, BSCpE, BSIE and BIT programs">College Guidance Counselor for BSCE, BSECE, BSEE, BSCpE, BSIE and BIT programs</option>
                            <option value="College Job Placement Officer">College Job Placement Officer</option>
                            <option value="College Property Custodian">College Property Custodian</option>
                            <option value="College Canvasser">College Canvasser</option>
                            <option value="College Inspector">College Inspector</option>
                            <option value="In-charge, College Reading Room">In-charge, College Reading Room</option>
                            <option value="In-charge, Material Testing Laboratory">In-charge, Material Testing Laboratory</option>
                            <option value="In-charge, Industrial Automation Center">In-charge, Industrial Automation Center</option>
                            <option value="In-charge, APPROTEC Center College Civil Security Officer">In-charge, APPROTEC Center College Civil Security Officer</option>
                            <option value="In-charge, Simulation and Math Laboratory">In-charge, Simulation and Math Laboratory</option>
                            <option value="CCL Head">CCL Head</option>
                            <option value="University Web Master">University Web Master</option>
                            <option value="Head, e-Learning Team">Head, e-Learning Team</option>
                            <option value="Faculty Member">Faculty Member</option>
                        </select>
                    </div>
                </label>
                <br>
                <div class="flex">
                    <label>
                        <select class="input" id="user_type" id="typeofuser" name="user_type" onchange="selectUser_type()">
                            <option value="">Type of User</option>
                            <option value="Implementor">Implementor</option>
                            <option value="Attendee">Attendee</option>
                        </select>
                    </label>
                    <label>
                        <select class="input" id="department" name="department" onchange="selectDepartment()">
                            <option value="">Department</option>
                            <?php
                            foreach ($departments as $department) {
                                echo '<option value="' . htmlspecialchars($department) . '">' . htmlspecialchars($department) . '</option>';
                            }
                            ?>
                        </select>
                    </label>
                </div>   
                <br>
                <div class="input-box">
                    <span id="departmentError" class="error-msg"></span>
                    <span id="user_typeError" class="error-msg"></span>
                    <input type="submit" name="register" class="submit" value="Register" onclick="submitForm()">
                </div>
            </div>
        </form>
    </div>
</div>   
<script>
    function selectPosition() {
        var positionTypeSelect = document.getElementById("position_type");
        var positionType = positionTypeSelect.value;

        var position = (positionType === "Faculty Member") ? "Faculty Member" : "Officials";

        // If "Faculty Member" is selected, set position to "Faculty Member", otherwise set it to "Officials"
        document.getElementById("position").value = position;
    }

    function submitForm() {
        var name = document.getElementById("name").value;
        var email = document.getElementById("recipient_email").value;
        var password = document.getElementById("password").value;
        var positionType = document.getElementById("position_type").value;
        var userType = document.getElementById("user_type").value;
        var department = document.getElementById("department").value;

        var position = (positionType === "Faculty Member") ? "Faculty Member" : "Officials";

        var formData = {
            name: name,
            user_email: email,
            password: password,
            position: position, // Directly set the position value here
            position_type: positionType,
            user_type: userType,
            department: department
        };

        $.ajax({
            type: "POST",
            url: "save_to_database.php",
            data: formData,
            success: function(response) {
                if (response === 'success') {
                    sendEmail();
                } else {
                    console.error("Failed to save data to the database");
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });

        return false;
    }

    const passwordInput = document.getElementById('password');
    const togglePasswordButton = document.getElementById('togglePassword');
    
    togglePasswordButton.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        // Change SVG icon based on password visibility
        const iconPath = type === 'password' ?
            "M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" :
            "M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24";
        togglePasswordButton.querySelector('svg path').setAttribute('d', iconPath);
    });
</script>
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
