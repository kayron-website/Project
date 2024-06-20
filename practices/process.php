<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Selected Names</title>
</head>
<body>

<h2>Selected Names</h2>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['names'])) {
    echo "<ul>";
    foreach ($_POST['names'] as $selectedName) {
        echo "<li>" . htmlspecialchars($selectedName) . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No names selected.</p>";
}
?>

</body>
</html>
