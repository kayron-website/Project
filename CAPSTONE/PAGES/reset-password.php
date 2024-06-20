 <?php

$token = $_GET["token"];

$token_hash = hash("sha256", $token);

$conn = require __DIR__ . "/config.php";

// $sql = "SELECT * FROM user_form
//         WHERE reset_token_hash = ?";
$sql = "SELECT * FROM user_form WHERE reset_token_hash = ? AND reset_token_expires_at > date('Y-m-d H:i:s')";

$stmt = $conn->prepare($sql);

$stmt->bind_param("s", $token_hash);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();
$checkTokenSql= "";

if ($user === null) {
    die("Token not found Or Expired");
}

// if (strtotime($user["reset_token_expires_at"]) <= time()) {
//     die("token has expired");
// }

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Untitled</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .login-dark {
  height:1000px;
  background-color: #2980ef;
  background-size:cover;
  position:relative;
}

.login-dark form {
  max-width:320px;
  width:90%;
  background-color:#1e2833;
  padding:40px;
  border-radius:4px;
  transform:translate(-50%, -50%);
  position:absolute;
  top:50%;
  left:50%;
  color:#fff;
  box-shadow:3px 3px 4px rgba(0,0,0,0.2);
}

.login-dark .illustration {
  text-align:center;
  padding:15px 0 20px;
  font-size:100px;
  color:#2980ef;
}

.login-dark form .form-control {
  background:none;
  border:none;
  border-bottom:1px solid #434a52;
  border-radius:0;
  box-shadow:none;
  outline:none;
  color:inherit;
}

.login-dark form .btn-primary {
  background:#214a80;
  border:none;
  border-radius:4px;
  padding:11px;
  box-shadow:none;
  margin-top:26px;
  text-shadow:none;
  outline:none;
}

.login-dark form .btn-primary:hover, .login-dark form .btn-primary:active {
  background:#214a80;
  outline:none;
}

.login-dark form .forgot {
  display:block;
  text-align:center;
  font-size:12px;
  color:#6f7a85;
  opacity:0.9;
  text-decoration:none;
}

.login-dark form .forgot:hover, .login-dark form .forgot:active {
  opacity:1;
  text-decoration:none;
}

.login-dark form .btn-primary:active {
  transform:translateY(1px);
}


    </style>    
</head>

<body>
    <div class="login-dark">
    <form method="post" action="process-reset-password.php">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

            <center><label for="email"><h2>Reset Password</h2></label></center>
            <div class="illustration"><i class="icon ion-ios-locked-outline"></i></div>
            <label for="email">New Password</label>

            <div class="form-group">
              <input class="form-control" type="password" name="password" id="password" placeholder="New Password">
            <label for="email">Repeat Password</label>
            <div class="form-group">
              <input class="form-control" type="password" name="password_confirmation" id="password_confirmation" placeholder="Repeat Password"></div>
            <button class="btn btn-primary btn-block" type="submit">Reset Password</button>
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>
</body>

</html>
