<?php

session_start();

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

$user_id = $_SESSION['user_id'];
if ($user_id === null) {
  header("Location: login.php");
  exit();
}

// Fetch user details
$sql = "SELECT u_name, u_email, u_phoneNum, user_name FROM registered_user WHERE u_id = :user_id";
$stmt = $conn->prepare($sql);
$stmt->execute([':user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
  echo "Error finding account.";
  exit();
}

$sql = "SELECT 
            b.booking_id, 
            b.location, 
            b.description,
            b.price, 
            b.payment_date, 
            bd.booking_date, 
            GROUP_CONCAT(p.name SEPARATOR ', ') AS photographers
        FROM 
            booking b
        JOIN 
            booking_dates bd ON b.booking_id = bd.booking_id
        LEFT JOIN 
            booked_photographers bp ON b.booking_id = bp.booking_id
        LEFT JOIN 
            photographer p ON bp.photographer_id = p.photographer_id
        WHERE 
            b.u_id = :user_id
        GROUP BY 
            b.booking_id";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

$conn = null;
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../CSS/styles.css" />
  <link
    rel="stylesheet"
    href="https://fonts.googleapis.com/icon?family=Material+Icons" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <title>User Account</title>
  <style>
    body,
    html {
      height: 100vh;
      width: 100vw;
      background-color: #f0f0f0;
      margin: 0;
      overflow-x: hidden;
      overflow-y: auto;
      display: flex;
      flex-direction: column;
    }

    nav {
      justify-content: space-evenly;
      display: flex;
    }

    nav a {
      text-decoration: none;
      border-radius: 20px;
      padding: 10px 20px 10px 20px;
      margin-top: 10px;
      font-family: Cambria, Cochin, Georgia, Times, "Times New Roman", serif;
      transition: all 0.7s ease-in-out;
      color: black;
    }

    .homeactive {
      background-color: rgb(232, 230, 230);
    }

    nav a:hover {
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    }

    .container {
      flex: 1;
      margin: 40px auto;
      width: 70%;
      height: auto;
      border-radius: 20px;
      background-color: white;
      display: flex;
      flex-direction: row;
      overflow: hidden;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.8);
    }


    .left-column {
      width: 40%;
      height: 100%;
      background: linear-gradient(135deg, #008080, #20B2AA);
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      overflow: hidden;
    }

    .left-column img {
      width: 50%;
      height: auto;
      object-fit: cover;

    }

    .right-column {
      width: 65%;
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: flex-start;
      align-items: center;
      overflow: hidden;
    }

    .information-container {
      font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
      font-size: 17px;
      font-weight: bold;
      color: white;
      text-align: left;
    }

    button {
      background-color: #f0f0f0;
      border: none;
      color: black;
      border: none;
      height: 30px;
      font-family: Cambria, Cochin, Georgia, Times, "Times New Roman", serif;
      border-radius: 10px;
      text-align: center;
      font-weight: bold;
      padding-left: 10px;
      padding-right: 10px;
    }

    .button-edit {
      background-color: #f0f0f0;
      border: none;
      color: black;
      border: none;
      height: 28px;
      font-family: Cambria, Cochin, Georgia, Times, "Times New Roman", serif;
      border-radius: 17px;
      align-content: center;
      font-weight: bold;
      font-size: 14px;
      padding: 5px 12px;
      text-decoration: none;
      width: auto;
      transition: background-color 0.3s;
    }

    .button-edit:hover {
      background-color: #d3e0e7;
    }

    .btns {
      margin-top: 30px;
      display: flex;
      flex-direction: row;
      justify-content: space-between;
      width: 80%;
    }

    .booking-container {
      margin-top: 20px;
      display: flex;
      flex-direction: column;
      justify-content: flex-start;
      align-items: center;
      width: 90%;
      height: 80%;
      overflow-y: scroll;
    }

    .display-information {
      width: 90%;
      margin-top: 20px;
      padding: 20px;
      border-radius: 20px;
      background-color: #f0f0f0;
      display: flex;
      flex-direction: row;
      justify-content: space-between;
      align-items: center;
    }

    .container-information {
      display: flex;
      justify-content: space-between;
      margin-bottom: 10px;
    }

    .text-display {
      font-weight: bold;
      width: 30%;
    }

    .text-container {
      background-color: rgba(255, 255, 255, 0.2);
      padding: 10px;
      border-radius: 10px;
      width: 65%
    }
  </style>
</head>

<body>
  <div class="navbar-space">
    <nav class="navigation-bar" style="margin-top:1%;">
      <a href="homepage.html">Home</a>
      <a class="homeactive" href="myAccount.php">My Account</a>
      <a href="booking.php">Booking</a>
      <a href="review.php">Reviews</a>
      <a href="All Profiles.html">Photographers' Profiles</a>
    </nav>
  </div>

  <div class="container">
    <div class="left-column">
      <img src="user.png" alt="User Image" style="border-radius: 50%; margin-bottom: 20px;"></img>
      <div class="information-container" style="width: 90%;">
        <div style="margin-bottom: 10px;">
          <div style="font-size: 15px; margin-bottom: 5px;">Name</div>
          <div style="background-color: rgba(255, 255, 255, 0.2); padding: 10px; border-radius: 10px;">
            <?php echo htmlspecialchars($user['u_name']); ?>
          </div>
        </div>
        <div style="margin-bottom: 10px;">
          <div style="font-size: 15px; margin-bottom: 5px;">Username</div>
          <div style="background-color: rgba(255, 255, 255, 0.2); padding: 10px; border-radius: 10px;">
            <?php echo htmlspecialchars($user['user_name']); ?>
          </div>
        </div>
        <div style="margin-bottom: 10px;">
          <div style="font-size: 15px; margin-bottom: 5px;">Email</div>
          <div style="background-color: rgba(255, 255, 255, 0.2); padding: 10px; border-radius: 10px;">
            <?php echo htmlspecialchars($user['u_email']); ?>
          </div>
        </div>
        <div style="margin-bottom: 10px;">
          <div style="font-size: 15px; margin-bottom: 5px;">Phone Number</div>
          <div style="background-color: rgba(255, 255, 255, 0.2); padding: 10px; border-radius: 10px; margin-bottom: 10px;">
            <?php echo htmlspecialchars($user['u_phoneNum']); ?>
          </div>
        </div>
      </div>
      <div class="btns" style="margin-top: 10px;">
        <a class="button-edit" href="editAccount.php">Edit details</a>
        <a class="button-edit" href="logout.php">Log out</a>
      </div>
    </div>
    <div class="right-column">
      <div style="color: #008080;font-family: Impact, Haettenschweiler, 'Arial Narrow Bold',sans-serif;letter-spacing: 2px;font-size: 30px; margin-top:30px;">
        Your Bookings
      </div>
      <div class="booking-container">
        <?php if (count($bookings) == 0) {
          echo "<p>No bookings found.</p>";
        } else {
          foreach ($bookings as $booking) { ?>
            <div class="display-information">
              <div style="display: flex; flex-direction: column; width: 100%;">
                <div class="container-information">
                  <div class="text-display">Description:</div>
                  <div class="text-container"><?php echo htmlspecialchars($booking['description']); ?></div>
                </div>
                <div class="container-information">
                  <div class="text-display">Location:</div>
                  <div class="text-container"><?php echo htmlspecialchars($booking['location']); ?></div>
                </div>
                <div class="container-information">
                  <div class="text-display">Price:</div>
                  <div class="text-container"><?php echo htmlspecialchars($booking['price']); ?></div>
                </div>
                <div class="container-information">
                  <div class="text-display">Payment Date:</div>
                  <div class="text-container"><?php echo $booking['payment_date'] ? htmlspecialchars($booking['payment_date']) : 'Not Paid'; ?></div>
                </div>
                <div class="container-information">
                  <div class="text-display">Booking Date:</div>
                  <div class="text-container"><?php echo htmlspecialchars($booking['booking_date']); ?></div>
                </div>
                <div class="container-information">
                  <div class="text-display">Photographers:</div>
                  <div class="text-container"><?php echo htmlspecialchars($booking['photographers']); ?></div>
                </div>
              </div>
            </div>
        <?php }
        }
        ?>
      </div>
    </div>
  </div>


</body>

</html>