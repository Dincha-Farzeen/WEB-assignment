<?php
// Database connection details
$dsn = "mysql:host=localhost;dbname=photography_collective";
$username = 'root';
$password = '';

try {
  $conn = new PDO($dsn, $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo "Database connection failed: " . $e->getMessage();
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get input values
  $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
  $newPassword = $_POST['new_password'];
  $confirmPassword = $_POST['confirm_password'];

  // Check if passwords match
  if ($newPassword !== $confirmPassword) {
    echo "Passwords do not match!";
    exit();
  }

  // Check if the email exists in the registered_user table
  $stmt = $conn->prepare("SELECT * FROM registered_user WHERE u_email = :u_email");
  $stmt->execute(['u_email' => $email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user) {
    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update password in the database
    $updateStmt = $conn->prepare("UPDATE registered_user SET pass_word = :pass_word WHERE u_email = :u_email");
    $updateStmt->execute(['pass_word' => $hashedPassword, 'u_email' => $email]);

    echo "<div id='popup' style='display: none; color: black; font-weight: bold;opacity: 0.8; position: fixed; top: 20px; left: 370px; background-color: white; border: 1px solid blaack; padding: 10px; border-radius: 20px; z-index: 1000;'>Your password has been successfully reset! You can now log in with your new password.</div>";
    echo "<script>
            var popup = document.getElementById('popup');
            popup.style.display = 'block';
            setTimeout(function() {
                popup.style.display = 'none';
            }, 3000); // The popup will disappear after 3 seconds
          </script>";
  } else {
    echo "Email not found.";
  }
}

$conn = null;
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <title>Reset Password</title>
  <style>
    body,
    html {
      margin: 0;
      padding: 0;
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

    .button {
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
      font-size: 14px;
      cursor: pointer;
    }

    .button:hover {
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
      width: 150px;
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
      left: 27%;
    }

    input[type="submit"]:hover {
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }

    .information-container {
      width: 80%;
      height: 200px;
      margin-top: 30px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
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
        <h2 style="
              font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande',
                'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
              letter-spacing: 5px;
              text-align: center;
              color: whitesmoke;
            ">
          Remember Your Password?
        </h2>
        <div style="
              font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande',
                'Lucida Sans Unicode', Geneva, Verdana, sans-serif;

              font-size: 13px;
              color: whitesmoke;
              text-align: center;
              margin-top: 15px;
              padding-bottom: 20px;
            ">
          If you've remembered your password, you <br />
          can return to the login page below
        </div>
        <div style="margin-top: 30px">
          <a class="button" href="login.html">Return to LogIn</a>
        </div>
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
          Reset your password
        </div>
        <div class="information-container">
          <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <label for="u_email"></label>
            <div>
              <input type="email" id="u_email" name="email" placeholder="Email" required />
            </div>
            <label for="pass_word"></label>
            <div>
              <input type="password" id="new_pass_word" name="new_password" placeholder="New Password" required />
            </div>
            <label for="pass_word"></label>
            <div>
              <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password"
                required />
            </div>
            <input type="submit" value="Reset Password" />
          </form>
        </div>
      </div>
    </div>
  </div>
</body>

</html>