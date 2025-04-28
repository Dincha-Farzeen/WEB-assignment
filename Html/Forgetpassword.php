<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$showPopup = false;
$popupMessage = '';
$popupType = '';

$dsn = "mysql:host=localhost;dbname=pc_laravel";
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['reset_email'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $showPopup = true;
            $popupMessage = 'Invalid email format.';
            $popupType = 'error';
        } else {
            // Check if email exists
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM registered_user WHERE u_email = ?");
            $stmt->execute([$email]);
            $count = $stmt->fetchColumn();

            if ($count == 0) {
                $showPopup = true;
                $popupMessage = 'Email not found in our records.';
                $popupType = 'error';
            } elseif (empty($newPassword) || empty($confirmPassword)) {
                $showPopup = true;
                $popupMessage = 'Please fill in all password fields.';
                $popupType = 'error';
            } elseif ($newPassword !== $confirmPassword) {
                $showPopup = true;
                $popupMessage = 'Passwords do not match.';
                $popupType = 'error';
            } else {
                // Hash the new password
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                // Update password
                $updateStmt = $pdo->prepare("UPDATE registered_user SET pass_word = ? WHERE u_email = ?");
                $updateStmt->execute([$hashedPassword, $email]);

                if ($updateStmt->rowCount() > 0) {
                    $showPopup = true;
                    $popupMessage = 'Password reset successfully!';
                    $popupType = 'success';
                } else {
                    $showPopup = true;
                    $popupMessage = 'Password update failed.';
                    $popupType = 'error';
                }
            }
        }
    }
} catch (PDOException $e) {
    $showPopup = true;
    $popupMessage = 'Database error: ' . $e->getMessage();
    $popupType = 'error';
}
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

    .popup {
      position: fixed;
      top: 20px;
      right: 20px;
      padding: 15px 25px;
      border-radius: 5px;
      z-index: 1000;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      animation: fadeInOut 3s ease-in-out;
      opacity: 0;
    }
    
    .popup.success {
      background-color: #4CAF50;
      color: white;
    }
    
    .popup.error {
      background-color: #f44336;
      color: white;
    }
    
    @keyframes fadeInOut {
      0% {
        opacity: 0;
        transform: translateY(-20px);
      }
      10% {
        opacity: 1;
        transform: translateY(0);
      }
      90% {
        opacity: 1;
        transform: translateY(0);
      }
      100% {
        opacity: 0;
        transform: translateY(-20px);
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
          Reset Password
        </div>
        <div style="
              font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande',
                'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
              font-size: 13px;
              color: whitesmoke;
              text-align: center;
              margin-top: 35px;
            ">
          Enter your email and new password to reset your account.
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
          Reset Your Password
        </div>

        <div class="information-container">
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="reset_email"></label>
            <div>
              <input type="email" id="reset_email" name="reset_email" placeholder="Email" required />
            </div>
            <label for="new_password"></label>
            <div>
              <input type="password" id="new_password" name="new_password" placeholder="New Password" required />
            </div>
            <label for="confirm_password"></label>
            <div>
              <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required />
            </div>
            <input type="submit" value="Reset" />
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    <?php if ($showPopup): ?>
    window.onload = function() {
      showPopup("<?php echo addslashes($popupMessage); ?>", "<?php echo $popupType; ?>");
    };
    <?php endif; ?>

    function showPopup(message, type) {
      const popup = document.createElement("div");
      popup.className = `popup ${type}`;
      popup.textContent = message;
      document.body.appendChild(popup);
      
      setTimeout(() => {
        popup.style.animation = 'fadeInOut 3s ease-in-out';
      }, 10);
      
      setTimeout(() => {
        popup.remove();
      }, 3000);
    }
  </script>
</body>

</html>