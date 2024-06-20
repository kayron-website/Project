<?php

$conn = new mysqli('localhost', 'root', '', 'scheduler');

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

return $conn;

?>


<!-- <?php

$conn = mysqli_connect('localhost','root','','scheduler');


?> -->