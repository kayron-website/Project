<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "scheduler";

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

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT uf.name, uf.user_type, uf.user_email, COALESCE(ts.available_status, 'Available') AS availability
        FROM user_form uf
        LEFT JOIN (SELECT DISTINCT user_email, available_status FROM table_sched) ts
        ON uf.user_email = ts.user_email
        WHERE uf.department = 'DIT'";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>List of People</title>
	<link rel="icon" type="image/x-icon" href="img/logo.png">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
	<link rel="stylesheet" href="css/member_list.css">
</head>
<body>
    <img class="department" src="img/DIT.png">
    <button id="backButton">Back</button>
	<div>
		<label for="availabilityFilter"></label>
		<select id="availabilityFilter" name="availabilityFilter" style="height: 35px;margin-left: 15px;margin-bottom: 10px;margin-top: 5px;border-radius: 10px;">
			<option value="All">All</option>
			<option value="Available">Available</option>
			<option value="Occupied">Occupied</option>
		</select>
	</div>
	<script>
	document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("backButton").addEventListener("click", function() {
        window.location.href = "admin_departments.php";
    });

    document.getElementById("availabilityFilter").addEventListener("change", function() {
        var selectedValue = this.value;
        var currentURL = window.location.href.split('?')[0]; // Get the current URL without query parameters
        var newURL = currentURL + '?filter=' + selectedValue; // Construct the new URL with the filter query parameter
        window.location.href = newURL; // Redirect to the new URL
    });
});
	</script>
    <table>
		<?php
		$selectedFilter = isset($_GET['filter']) ? $_GET['filter'] : 'All';
		
		if ($result->num_rows > 0) {
			echo "<table>
				<tr>
					<th>Name</th>
					<th>Role</th>
					<th>Email</th>
					<th>Availability</th>
				</tr>";

			// Output data from each row
			while ($row = $result->fetch_assoc()) {
				if ($selectedFilter === 'All' || $row["availability"] === $selectedFilter) {
					echo "<tr>
						<td>" . $row["name"] . "</td>
						<td>" . $row["user_type"] . "</td>
						<td>" . $row["user_email"] . "</td>
						<td>" . $row["availability"] . "</td>
					</tr>";
				}
			}

			echo "</table>";
		} else {
			echo "No records found for the DCEA department";
		}

		// Close the database connection
		$conn->close();
		?>
    </table>
</body>

</html>
