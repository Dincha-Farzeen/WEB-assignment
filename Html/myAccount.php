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
}


$user_id = $_SESSION['user_id']; // Fetch user data based on session user_id
if ($user_id === null) {
  header("Location: login.php");
  exit();
}

$sql = "SELECT u_name, u_email, u_phoneNum, user_name FROM registered_user WHERE u_id = :user_id";
$stmt = $conn->prepare($sql);
$stmt->execute([':user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
  echo "Error finding account.";
  exit();
}

$sql = "SELECT b.description, b.location, b.price, b.payment_date, bd.booking_date, b.numberOfDays,
               GROUP_CONCAT(p.name SEPARATOR ', ') AS photographers
        FROM booking b
        JOIN booking_dates bd ON b.booking_id = bd.booking_id
        LEFT JOIN booked_photographers bp ON b.booking_id = bp.booking_id
        LEFT JOIN photographer p ON bp.photographer_id = p.photographer_id
        WHERE b.u_id = :user_id
        GROUP BY b.booking_id";

$stmt = $conn->prepare($sql);
$stmt->execute([':user_id' => $user_id]);
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
      width: 35%;
      height: 100%;
      background-color: #008080;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      overflow: hidden;
    }

    .left-column img {
      width: 90%;
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
      width: 80%;
      max-height: 300px;
      margin-top: 2px;
      display: flex;
      align-items: flex-start;
      justify-content: flex-start;
      overflow-y: auto;
    }

    table {
      width: 100%;
      margin: 15px 0;
    }

    th,
    td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
      border: none;
    }

    th {
      background-color: rgba(0, 128, 128, 0.34);
    }

    .additional-info {
      display: none;
      height: max-content;
    }

    .show {
      display: table-row;
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
      border-radius: 10px;
      align-content: center;
      font-weight: bold;
      font-size: 14px;
      padding-left: 10px;
      padding-right: 10px;
      text-decoration: none;
      width: auto;
    }

    .btns {
      margin-top: 10px;
      display: flex;
      flex-direction: row;
      justify-content: space-between;
      width: 80%;
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
      <img src="user.png"></img>
      <div class="information-container" style="font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman',serif;
        font-size: 17px; font-weight: bold; color:white;">
        Name:
        <?php echo htmlspecialchars($user['u_name']); ?>
        <br />
        Username:
        <?php echo htmlspecialchars($user['user_name']); ?>
        <br />
        Email:
        <?php echo htmlspecialchars($user['u_email']); ?>
        <br />
        Phone Number:
        <?php echo htmlspecialchars($user['u_phoneNum']); ?>
        <br />
      </div>
      <div class="btns">
        <a class="button-edit" href="editAccount.php">Edit details</a>
        <a class="button-edit" href="logout.php">Log out</a>
      </div>
    </div>
    <div class="right-column">
      <div style="color: #008080;font-family: Impact, Haettenschweiler, 'Arial Narrow Bold',sans-serif;letter-spacing: 2px;font-size: 30px; margin-top:30px;">
        Your Bookings
      </div>
      <div class="information-container" style="font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman',serif;
        font-size: 15px;font-weight: bold;">
        <table>
          <thead>
            <tr>
              <th>Booking Date</th>
              <th>Description</th>

            </tr>
          </thead>
          <tbody>
            <?php foreach ($bookings as $booking): ?>
              <tr>
                <td><?php echo htmlspecialchars($booking['booking_date']); ?></td>
                <td><?php echo htmlspecialchars($booking['description']); ?></td>
                <td><button onclick="toggleDetails(this)">View Details</button></td>
              </tr>
              <tr class="additional-info" style="font-weight:lighter;">
                <td colspan="3">
                  <?php echo htmlspecialchars($booking['numberOfDays']); ?>-day event held at
                  <?php echo htmlspecialchars($booking['location']); ?> quoted at Rs
                  <?php echo htmlspecialchars($booking['price']); ?> with an outstanding balance of Rs
                  <?php echo htmlspecialchars($booking['price']); ?>. <br>
                  <strong>Photographer(s): </strong>
                  <?php echo htmlspecialchars($booking['photographers'] ?: 'Not Assigned'); ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>

        </table>

        <script>
          function toggleDetails(button) {
            const row = button.closest('tr');
            const nextRow = row.nextElementSibling;

            if (nextRow && nextRow.classList.contains('additional-info')) {
              nextRow.classList.toggle('show');
            }
          }
        </script>

      </div>
    </div>
  </div>


</body>

</html>