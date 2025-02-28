<?php
session_start();
$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['reset_email'])) {
  $email = $_POST['email'] ?? '';
  $password = $_POST['password'] ?? '';
  if (empty($email) || empty($password)) {
    $error = 'Email and password are required.';
  } else {
    try {
      $dsn = "mysql:host=localhost;dbname=photography_collective";
      $username = 'root';
      $password = '';
      $conn = new PDO($dsn, $username, $password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      echo "Database connection failed: " . $e->getMessage();
    }

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    //validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      echo "Invalid e-mail format.";
      exit();
    }

    $sql = "SELECT * FROM registered_user WHERE u_email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);

    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
      if (password_verify($password, $user['pass_word'])) {
        $_SESSION['user_id'] = $user['u_id'];   // Store the user ID as fetched from database
        header("Location: homepage.html");
        exit();
      } else {
        $error = 'Invalid email or password.';
      }
    } else {
      $error = 'No user found with the provided email.';
    }
  }
}

// Handle password reset
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_email'])) {
  $reset_email = $_POST['reset_email'];
  $new_password = $_POST['new_password'];
  $confirm_password = $_POST['confirm_password'];

  if ($new_password === $confirm_password) {
    try {
      $dsn = "mysql:host=localhost;dbname=photography_collective";
      $username = 'root';
      $password = '';
      $conn = new PDO($dsn, $username, $password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

      $sql = "UPDATE registered_user SET pass_word = :new_password WHERE u_email = :reset_email";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':new_password', $hashed_password);
      $stmt->bindParam(':reset_email', $reset_email);

      if ($stmt->execute()) {
        $success = true;
      } else {
        echo "Error updating password.";
      }
    } catch (PDOException $e) {
      echo "Database connection failed: " . $e->getMessage();
    }
  } else {
    echo "<script>document.getElementById('passwordError').style.display = 'block';</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <title>LogIn</title>
  <style>
    body,
    html {
      margin: 0;
      padding: 0;
    }

    input[type="email"],
    input[type="password"] {
      background-color: rgb(219, 216, 216);
      width: 300px;
      padding: 10px;
      border: transparent;
      border-radius: 20px;
      transition: border 0.3s ease;
      animation: fadeIn 0.5s ease forwards;
      transition: all 0.3s ease;
      animation-delay: 0.5s;
      position: relative;
      margin: 5px;
    }

    input[type="email"]:focus,
    input[type="password"]:focus {
      border: 1px solid rgb(116, 116, 116);
      outline: none;
    }

    input[type="email"]:hover,
    input[type="password"]:hover {
      box-shadow: 0 4px 3px rgba(0, 0, 0, 0.1);
    }

    input[type="submit"] {
      width: 110px;
      padding: 10px;
      margin-top: 30px;
      font-family: "Lucida Sans", "Lucida Sans Regular", "Lucida Grande",
        "Lucida Sans Unicode", Geneva, Verdana, sans-serif;
      background-color: #008080;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 95%;
      transition: all 0.3s ease;
      animation: fadeIn 0.5s ease forwards;
      animation-delay: 0.7s;
      position: relative;
      left: 33%;
    }

    input[type="submit"]:hover {
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }

    a {
      text-align: center;
      display: block;
      margin-top: 10px;
      color: black;
      font-size: 14px;
      opacity: 0;
      animation: fadeIn 0.5s ease forwards;
      animation-delay: 1s;
    }

    a:hover {
      text-decoration: underline;
    }

    /* Animation Keyframes */
    @keyframes fadeIn {
      from {
        opacity: 0;
      }

      to {
        opacity: 1;
      }
    }

    .container {
      width: 800px;
      height: 500px;
      border-radius: 20px;
      background-color: #f0f0f0;
      display: flex;
      flex-direction: row;
      justify-content: space-between;
      overflow: hidden;
      box-shadow: 0 1px 20px rgba(0, 0, 0, 0.3);
    }

    .left-column {
      width: 45%;
      height: 100%;
      background: linear-gradient(to right,
          #0f6969,
          #008080,
          #019c9c,
          #01b3b3);
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }

    .sign-up a {
      background-color: transparent;
      color: whitesmoke;
      border: 1px solid whitesmoke;
      padding: 13px 40px;
      transition: all 0.5s ease;
      border-radius: 30px;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      font-family: "Lucida Sans", "Lucida Sans Regular", "Lucida Grande",
        "Lucida Sans Unicode", Geneva, Verdana, sans-serif;
      margin-top: 30px;
    }

    .sign-up a:hover {
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }

    .home-text a {
      color: whitesmoke;
      font-family: "Lucida Sans", "Lucida Sans Regular", "Lucida Grande",
        "Lucida Sans Unicode", Geneva, Verdana, sans-serif;
      display: inline;
      font-size: 11px;
      text-decoration: none;
    }

    .home-text a:hover {
      text-decoration: underline;
    }

    .right-column {
      width: 55%;
      height: 100%;
      background: linear-gradient(to right, white, whitesmoke);
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      overflow: hidden;
    }

    a {
      text-decoration: none;
      padding: 0 7px 0 7px;
    }

    a:hover {
      text-decoration: none;
    }

    .information-container {
      width: 80%;
      height: 200px;
      margin-top: 30px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }

    .forget-password a {
      color: gray;
      font-family: "Lucida Sans", "Lucida Sans Regular", "Lucida Grande",
        "Lucida Sans Unicode", Geneva, Verdana, sans-serif;
      font-size: 12px;
      margin-top: 17px;
    }

    .forget-password a:hover {
      text-decoration: underline;
    }

    .modal {
      display: none;
      position: fixed;
      z-index: 1;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0, 0, 0, 0.5);
      justify-content: center;
      align-items: center;
    }

    .modal-content {
      background-color: #fff;
      margin: auto;
      padding: 20px;
      border: 1px solid #888;
      width: 80%;
      max-width: 400px;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
      animation: slideIn 0.3s ease-out;
    }

    .close {
      color: #aaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
    }

    .close:hover,
    .close:focus {
      color: black;
      text-decoration: none;
      cursor: pointer;
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-group label {
      display: flex;
      margin-bottom: 5px;
    }

    .form-group input[type="email"],
    .form-group input[type="password"] {
      width: 90%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    .form-group input[type="submit"] {
      width: 35%;
      padding: 10px;
      background-color: #008080;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
      margin-left: 110px;
    }

    .form-group input[type="submit"]:hover {
      background-color: #006666;
    }

    .success-message {
      position: fixed;
      top: 20px;
      right: 20px;
      background-color: rgb(56, 157, 60);
      color: white;
      padding: 15px;
      border-radius: 5px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
      animation: fadeInOut 3s ease-in-out;
    }

    @keyframes slideIn {
      from {
        transform: translateY(-50px);
        opacity: 0;
      }

      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    @keyframes fadeInOut {

      0%,
      100% {
        opacity: 0;
      }

      50% {
        opacity: 1;
      }
    }
  </style>
</head>

<body>
  <div style="
        background-image: url(login.jpg);
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        width: 100%;
        height: 100vh;
        overflow: hidden;
        justify-content: center;
        align-items: center;
        display: flex;
      ">
    <div class="container">
      <div class="left-column">
        <div style="
              font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande',
                'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
              font-size: 30px;
              letter-spacing: 5px;
              color: whitesmoke;
              display: inline-block;
            ">
          Hello, Friend!
        </div>
        <div style="
              font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande',
                'Lucida Sans Unicode', Geneva, Verdana, sans-serif;

              font-size: 13px;
              color: whitesmoke;
              text-align: center;
              margin-top: 35px;
            ">
          Enter your personal details and<br />
          share your journey with us
        </div>
        <div class="sign-up"><a href="createAcc.html">Sign up</a></div>
        <hr style="width: 250px; margin-top: 60px" />
        <div>
          <i class="fas fa-arrow-left" style="color: whitesmoke; font-size: 12px"></i>
          <div class="home-text" style="display: inline">
            <a href="homepage.html">Cancel and return to home page</a>
          </div>
        </div>
      </div>
      <div class="right-column">
        <div style="
              color: #008080;
              font-family: Impact, Haettenschweiler, 'Arial Narrow Bold',
                sans-serif;
              letter-spacing: 2px;
              font-size: 30px;
            ">
          Continue your journey!
        </div>

        <div class="information-container">
          <form action="login.php" method="post">
            <label for="u_email"></label>
            <div>
              <input type="email" id="u_email" name="email" placeholder="Email" required />
            </div>
            <label for="pass_word"></label>
            <div>
              <input type="password" id="pass_word" name="password" placeholder="Password" required />
            </div>
            <div class="forget-password">
              <a href="#" id="forgotPasswordLink">Forgot Password?</a>
            </div>
            <input type="submit" value="Log In" />
          </form>
        </div>
        <?php if ($error) { ?>
          <div style="color:red; margin-top: 10px;"><?php echo htmlspecialchars($error); ?></div>
        <?php } ?>
      </div>
    </div>
  </div>

  <div id="forgotPasswordModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2>Reset your Password</h2>
      <form id="forgotPasswordForm" action="login.php" method="post">
        <div class="form-group">
          <label for="reset_email">Email:</label>
          <input type="email" id="reset_email" name="reset_email" required>
        </div>
        <div class="form-group">
          <label for="new_password">New Password:</label>
          <input type="password" id="new_password" name="new_password" required>
        </div>
        <div class="form-group">
          <label for="confirm_password">Confirm Password:</label>
          <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        <div class="form-group">
          <input type="submit" value="Reset Password">
        </div>
        <div id="passwordError" style="color: red; display: none;">Passwords do not match!</div>
        <input type="hidden" id="successFlag" value="<?php echo $success ? 'true' : 'false'; ?>">
      </form>
    </div>
  </div>
  <div id="successMessage" class="success-message" style="display: none;">
    Password has been reset successfully!
  </div>

  <script>
    var modal = document.getElementById("forgotPasswordModal");

    var link = document.getElementById("forgotPasswordLink");

    var span = document.getElementsByClassName("close")[0];

    var successMessage = document.getElementById("successMessage");

    var successFlag = document.getElementById("successFlag").value;

    link.onclick = function(event) {
      event.preventDefault();
      modal.style.display = "flex";
    }

    span.onclick = function() {
      modal.style.display = "none";
    }

    window.onclick = function(event) {
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }

    document.getElementById("forgotPasswordForm").onsubmit = function(event) {
      var newPassword = document.getElementById("new_password").value;
      var confirmPassword = document.getElementById("confirm_password").value;

      if (newPassword !== confirmPassword) {
        event.preventDefault();
        document.getElementById("passwordError").style.display = "block";
      } else {
        document.getElementById("passwordError").style.display = "none";
      }
    }

    if (successFlag === 'true') {
      successMessage.style.display = "block";
      setTimeout(function() {
        successMessage.style.display = "none";
      }, 3000);
    }
  </script>
</body>

</html>