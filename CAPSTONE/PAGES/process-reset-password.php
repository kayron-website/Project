<?php

$token = $_POST["token"];

$token_hash = hash("sha256", $token);

$conn = require __DIR__ . "/config.php";

// $sql = "SELECT * FROM user_form
//         WHERE reset_token_hash = ?";
$checkTokenSql= "SELECT * FROM user_form WHERE reset_token_hash = ? AND reset_token_expires_at > UTC_TIMESTAMP() ";

$stmt = $conn->prepare($checkTokenSql);

$stmt->bind_param("s", $token_hash);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if ($user === null) {
    die("Token not found or Expired!");
}

if (strtotime($user["reset_token_expires_at"]) <= time()) {
    die("token has expired");
}

// if (strlen($_POST["password"]) < 8) {
//     die("Password must be at least 8 characters");
// }

// if ( ! preg_match("/[a-z]/i", $_POST["password"])) {
//     die("Password must contain at least one letter");
// }

if ( ! preg_match("/[0-9]/", $_POST["password"])) {
    die("Password must contain at least one number");
}

if ($_POST["password"] !== $_POST["password_confirmation"]) {
    die("Passwords must match");
}

$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

$sql = "UPDATE user_form
        SET password = ?,
            reset_token_hash = NULL,
            reset_token_expires_at = NULL
        WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("ss", $password_hash, $user["id"]);

$stmt->execute();

header("Location: index.php");