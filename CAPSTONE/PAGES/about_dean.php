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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/about_us.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <title>Our-Team</title>
	<link rel="icon" type="image/x-icon" href="img/logo.png">
</head>

<body>
    <div class="container">
		<div class="button-container">
			<a href="homepage_dean.php">
				<button class="button">
					<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 576 512" stroke-width="0" fill="currentColor" stroke="currentColor" class="icon">
						<path d="M575.8 255.5c0 18-15 32.1-32 32.1h-32l.7 160.2c0 2.7-.2 5.4-.5 8.1V472c0 22.1-17.9 40-40 40H456c-1.1 0-2.2 0-3.3-.1c-1.4 .1-2.8 .1-4.2 .1H416 392c-22.1 0-40-17.9-40-40V448 384c0-17.7-14.3-32-32-32H256c-17.7 0-32 14.3-32 32v64 24c0 22.1-17.9 40-40 40H160 128.1c-1.5 0-3-.1-4.5-.2c-1.2 .1-2.4 .2-3.6 .2H104c-22.1 0-40-17.9-40-40V360c0-.9 0-1.9 .1-2.8V287.6H32c-18 0-32-14-32-32.1c0-9 3-17 10-24L266.4 8c7-7 15-8 22-8s15 2 21 7L564.8 231.5c8 7 12 15 11 24z" fill="white"/>
					</svg>
				</button>
			</a>
			<a href="profile_dean.php">
				<button class="button">
					<svg xmlns="http://www.w3.org/2000/svg" width="1.3em" height="1.3em" viewBox="0 0 576 512" stroke-width="0" fill="currentColor" stroke="currentColor" class="icon">
						<path d="M512 80c8.8 0 16 7.2 16 16V416c0 8.8-7.2 16-16 16H64c-8.8 0-16-7.2-16-16V96c0-8.8 7.2-16 16-16H512zM64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H512c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H64zM208 256a64 64 0 1 0 0-128 64 64 0 1 0 0 128zm-32 32c-44.2 0-80 35.8-80 80c0 8.8 7.2 16 16 16H304c8.8 0 16-7.2 16-16c0-44.2-35.8-80-80-80H176zM376 144c-13.3 0-24 10.7-24 24s10.7 24 24 24h80c13.3 0 24-10.7 24-24s-10.7-24-24-24H376zm0 96c-13.3 0-24 10.7-24 24s10.7 24 24 24h80c13.3 0 24-10.7 24-24s-10.7-24-24-24H376z"  fill="white"/>
					</svg>
				</button>
			</a>
			<a href="members_dean.php">
				<button class="button">
					<svg xmlns="http://www.w3.org/2000/svg" width="1.3em" height="1.3em" viewBox="0 0 640 512" stroke-width="0" fill="currentColor" stroke="currentColor" class="icon">
						<path d="M211.2 96a64 64 0 1 0 -128 0 64 64 0 1 0 128 0zM32 256c0 17.7 14.3 32 32 32h85.6c10.1-39.4 38.6-71.5 75.8-86.6c-9.7-6-21.2-9.4-33.4-9.4H96c-35.3 0-64 28.7-64 64zm461.6 32H576c17.7 0 32-14.3 32-32c0-35.3-28.7-64-64-64H448c-11.7 0-22.7 3.1-32.1 8.6c38.1 14.8 67.4 47.3 77.7 87.4zM391.2 226.4c-6.9-1.6-14.2-2.4-21.6-2.4h-96c-8.5 0-16.7 1.1-24.5 3.1c-30.8 8.1-55.6 31.1-66.1 60.9c-3.5 10-5.5 20.8-5.5 32c0 17.7 14.3 32 32 32h224c17.7 0 32-14.3 32-32c0-11.2-1.9-22-5.5-32c-10.8-30.7-36.8-54.2-68.9-61.6zM563.2 96a64 64 0 1 0 -128 0 64 64 0 1 0 128 0zM321.6 192a80 80 0 1 0 0-160 80 80 0 1 0 0 160zM32 416c-17.7 0-32 14.3-32 32s14.3 32 32 32H608c17.7 0 32-14.3 32-32s-14.3-32-32-32H32z"  fill="white"/>
					</svg>
				</button>
			</a>
			<a href="calendar_dean.php">
				<button class="button">
					<svg xmlns="http://www.w3.org/2000/svg" width="0.9em" height="0.9em" viewBox="0 0 448 512" stroke-width="0" fill="currentColor" stroke="currentColor" class="icon">
						<path d="M128 0c17.7 0 32 14.3 32 32V64H288V32c0-17.7 14.3-32 32-32s32 14.3 32 32V64h48c26.5 0 48 21.5 48 48v48H0V112C0 85.5 21.5 64 48 64H96V32c0-17.7 14.3-32 32-32zM0 192H448V464c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V192zm64 80v32c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16V272c0-8.8-7.2-16-16-16H80c-8.8 0-16 7.2-16 16zm128 0v32c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16V272c0-8.8-7.2-16-16-16H208c-8.8 0-16 7.2-16 16zm144-16c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16V272c0-8.8-7.2-16-16-16H336zM64 400v32c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16V400c0-8.8-7.2-16-16-16H80c-8.8 0-16 7.2-16 16zm144-16c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16V400c0-8.8-7.2-16-16-16H208zm112 16v32c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16V400c0-8.8-7.2-16-16-16H336c-8.8 0-16 7.2-16 16z"/>
					</svg>
				</button>
			</a>
		</div>
        <h1>Our Team<span></span></h1>
        <div class="sub-container">
            <div class="teams">
				<div class="image">
					<img src="img/kayron.png" height="100px" alt="logo">
				</div>
                <div class="name">Kayron Mark Burzon</div>
                <div class="desig">Full Stack Developer</div>
                <div class="about">
					“Coding is not just about writing lines of code; 
					it's about crafting a symphony of logic and creativity.”
				</div>

                <div class="social-links">
                    <a href="https://www.facebook.com/profile.php?id=100006869026617"><i class="fa-brands fa-facebook"></i></a>
                    <a href="https://github.com/kayron-website"><i class="fa-brands fa-github"></i></a>
                </div>
            </div>

            <div class="teams">
				<img src="img/none.png" height="100px" alt="logo">
                <div class="name">Bea Bianca Diago</div>
                <div class="desig">Quality Assurance</div>
                <div class="about">
					“Software testing is a sport like ㅤ hunting, it's bughunting.”
					ㅤ ㅤ ㅤ ㅤ ㅤ ㅤㅤ ㅤ ㅤ ㅤ ㅤ ㅤㅤ ㅤ ㅤ ㅤ ㅤ ㅤㅤ ㅤ ㅤ ㅤ
				</div>

                <div class="social-links">
                    <a href="https://www.facebook.com/beabianca.diago"><i class="fa-brands fa-facebook"></i></a>
                    <a href="https://www.instagram.com/berbeya/"><i class="fa-brands fa-instagram"></i></a>
                    <a href="https://github.com/bbrd23/bbrd23"><i class="fa-brands fa-github"></i></a>
                </div>
            </div>

            <div class="teams">
				<img src="img/none.png" height="100px" alt="logo">
                <div class="name">John Jorem Quiogue</div>
                <div class="desig">Quality Assurance</div>
                <div class="about">
					“Quality is a product of a conflict ㅤbetween programmers and ㅤ ㅤ ㅤ ㅤ ㅤtesters.”
					ㅤ ㅤ ㅤ ㅤ ㅤ ㅤㅤ ㅤ ㅤ ㅤ ㅤ ㅤ
				</div>

                <div class="social-links">
					<a href="https://www.facebook.com/jorem.quiogue?mibextid=ZbWKwL"><i class="fa-brands fa-facebook"></i></a>
                    <a href="https://instagram.com/jaykyo0"><i class="fa-brands fa-instagram"></i></a>
                    <a href="https://github.com/jaykyo20/jaykyo20.github.io/commits?author=jaykyo20"><i class="fa-brands fa-github"></i></a>
                </div>
            </div>

			<div class="teams">
				<img src="img/none.png" height="100px" alt="logo">
                <div class="name">Leo Bon Tan</div>
                <div class="desig">Back End Developer</div>
                <div class="about">
					“A bug is not a setback; it's an opportunity to strengthen your ㅤ  ㅤproblem-solving skills.”
					ㅤ ㅤ ㅤ ㅤ ㅤ ㅤ
				</div>

                <div class="social-links">
					<a href=":https://www.facebook.com/leobon.tan.3?mibextid=ZbWKwL"><i class="fa-brands fa-facebook"></i></a>
                    <a href="@leobontan"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"><i class="fa-brands fa-github"></i></a>
                </div>
            </div>
        </div>
    </div>
	<div class="containers">
		<div class="service-wrapper">
			<div class="service">
				<h1>Our Capstone<span></span></h1>
				<div class="cards">
					<div class="card">
						<i class="fa-solid fa-user-pen"></i>
						<h2>Implementor</h2>
						<p>This type of user can create an event in calendar and implementor can choose who will be a participant in the event</p>
					</div>
					<div class="card">
						<i class="fa-solid fa-user-tie"></i>
						<h2>Users</h2>
						<p>Can also add schedule but only in the profile page so that the other users can see if the person is Available or Occupied</p>
					</div>
					<div class="card">
						<i class="fa-regular fa-window-maximize"></i>
						<h2>The Website</h2>
						<p>A website is build only for the professors/instructors who will be needed an usable site to make a noticable and help them to not forget the schedule given</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>

</html>