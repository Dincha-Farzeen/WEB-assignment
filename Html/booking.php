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


$user_id = $_SESSION['user_id'];
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $photographerName = $_POST['photographer_name'] ?? '';
} else {
  $photographerName = "Choose photographer(s)";
}
$conn = null;
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Booking</title>

  <style>
    body,
    html {
      margin: 0;
      padding: 0;
      height: 100%;
      font-family: "Lucida Sans", "Lucida Sans Regular", "Lucida Grande",
      "Lucida Sans Unicode", Geneva, Verdana, sans-serif;
      font-size:14px;
    }

    .background {
      display: flex;
      height: 100vh;
    }

    .image {
      flex: 0 0 55%;
      background-image: url('bookingImage.jpg');
      background-size: cover;
      background-position: center;
    }

    .content {
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 10px;
      background-color: rgba(70, 43, 16, 0.39);
      overflow-y: scroll;
 
    }

    .form-container {
      width: 85%;
      height: 300px;
      margin-top: 30px;
      display: inline-flex;
      align-items: center;
      justify-content: center;

    }

    form {
      display: inline-flex;
      align-items: center;
      flex-direction: column;
      width: 100%;
    }

    input[type="submit"] {
      width: auto;
      padding: 10px;
      margin-top: 30px;
      font-family: "Lucida Sans", "Lucida Sans Regular", "Lucida Grande",
        "Lucida Sans Unicode", Geneva, Verdana, sans-serif;
      background-color: rgb(88, 67, 48);
      color: white;
      border: none;
      border-radius: 20px;
      cursor: pointer;
      font-size: 95%;
      transition: all 0.3s ease;
      animation: fadeIn 0.5s ease forwards;
      animation-delay: 0.7s;
      position: relative;

    }

    input[type="submit"]:hover {
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }

    input[type="text"],
    input[type="numeric"],
    input[type="date"] {
      background-color: rgb(219, 216, 216);
      width: 80%;
      padding: 10px;
      border: transparent;
      border-radius: 20px;
      transition: border 0.3s ease;
      animation: fadeIn 0.5s ease forwards;
      transition: all 0.3s ease;
      animation-delay: 0.5s;
      position: relative;
      margin: 5px;
      font-family: "Lucida Sans", "Lucida Sans Regular", "Lucida Grande",
        "Lucida Sans Unicode", Geneva, Verdana, sans-serif;
        font-size:12px;
    }

    input[type="text"]:focus,
    input[type="numeric"]:focus,
    textarea:focus {
      border: 1px solid rgb(116, 116, 116);
      outline: none;
    }

    input[type="text"]:hover,
    textarea:hover,
    input[type="date"]:hover,
    input[type="numeric"]:hover {
      box-shadow: 0 4px 3px rgba(0, 0, 0, 0.1);
    }

    textarea {
      background-color: rgb(219, 216, 216);
      width: 80%;
      height: 80px;
      padding: 10px;
      border: transparent;
      border-radius: 20px;
      transition: border 0.3s ease;
      animation: fadeIn 0.5s ease forwards;
      transition: all 0.3s ease;
      animation-delay: 0.5s;
      position: relative;
      margin: 5px;
      font-family: "Lucida Sans", "Lucida Sans Regular", "Lucida Grande",
        "Lucida Sans Unicode", Geneva, Verdana, sans-serif;
    }

    a {
      width: auto;
      padding: 8px;
      margin-top: 30px;
      font-family: "Lucida Sans", "Lucida Sans Regular", "Lucida Grande",
        "Lucida Sans Unicode", Geneva, Verdana, sans-serif;
      background-color: rgb(88, 67, 48);
      color: white;
      border: none;
      border-radius: 20px;
      cursor: pointer;
      font-size: 80%;
      transition: all 0.3s ease;
      animation: fadeIn 0.5s ease forwards;
      animation-delay: 0.7s;
      position: relative;
      align-self: flex-end;
      text-decoration: none;
    }


    a:hover {
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }

    .photographer-options {
      display: none;
      position: absolute;
      background-color: #f9f9f9;
      min-width: 200px;
      box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
      z-index: 1;
      border-radius: 5px;
      padding: 5px;
      margin-top: 0px;
    }

    .photographer-options div {
      padding: 5px;
      cursor: pointer;
    }

    .photographer-options div:hover {
      background-color: #ddd;
    }

    /* Show the options when hovering over the text field */
    .dropdown:hover .photographer-options {
      display: block;
    }

    input[type="text"] {
      width: 390px;
    }

    .btn-cancel {
      width: auto;
      padding: 10px;
      margin-top: 30px;
      font-family: "Lucida Sans", "Lucida Sans Regular", "Lucida Grande",
        "Lucida Sans Unicode", Geneva, Verdana, sans-serif;
      background-color: rgb(88, 67, 48);
      color: white;
      border: none;
      border-radius: 20px;
      cursor: pointer;
      font-size: 95%;
      transition: all 0.3s ease;
      animation: fadeIn 0.5s ease forwards;
      animation-delay: 0.7s;
      position: relative;
    }

    .btn-cancel:hover {
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }

    .modal {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      display: flex;
      justify-content: center;
      align-items: center;
      backdrop-filter: blur(10px);
    }

    .modal-content {
      background-color: white;
      padding: 20px;
      border-radius: 8px;
      text-align: left;
      position: relative;
      width: 80%;
      max-width: 500px;
      max-height: 80%;
      overflow-y: auto;
    }

    .modal-content h2 {
      text-align: center;
    }

    .close {
      position: absolute;
      top: 10px;
      right: 15px;
      font-size: 20px;
      font-weight: bold;
      cursor: pointer;
    }

    .message-box {
      max-height: 150px;
      overflow-y: auto;
      border: 1px solid #ccc;
      padding: 10px;
      border-radius: 5px;
      background-color: #f9f9f9;
    }
  </style>
</head>

<body>
  <div class="background">
    <div class="image"></div>
    <div class="content">
      <h1 style="margin-bottom: 0px;">Ready for the spotlight?</h1>
      <p style="font-weight:bold;">Let's get booking, <?php echo htmlspecialchars($user['u_name']); ?>.</p>

      <div>
        <p>
          <strong>E-mail:</strong> <?php echo htmlspecialchars($user['u_email']); ?> <br>
          <strong> Number:</strong> <?php echo htmlspecialchars($user['u_phoneNum']); ?> <br>
          <br>
          <i style="font-size: 14px; text-decoration:italic;">In case of any discrepencies, please update your information<br>
          and come back again to continue booking.</i>
        </p>
        <a href="myAccount.php">Go to account settings</a></a>
      </div>

      <div class="form-container">
        <form id="booking-form" action='../Html/request.php' method='POST'>
          <div class="dropdown">
            <label for="photographer_name"></label>
            <input type="text" id="photographer_name" name="photographer_name"
              value="<?php echo htmlspecialchars($photographerName); ?>" placeholder="Photographer" readonly>
            <div class="photographer-options">
              <!-- Hardcoded photographer options -->
              <div onclick="updateTextField('Alex Johnson')">Alex Johnson</div>
              <div onclick="updateTextField('Sam Lee')">Sam Lee</div>
              <div onclick="updateTextField('Olivia Martinez')">Olivia Martinez</div>
              <div onclick="updateTextField('Liam Smith')">Liam Smith</div>
              <div onclick="updateTextField('Emma Davidson')">Emma Davidson</div>
              <div onclick="updateTextField('Sophia Brown')">Sophia Brown</div>
              <div onclick="updateTextField('Francis Davis')">Francis Davis</div>
              <div onclick="updateTextField('Team of Photographers')">Team of Photographers</div>
            </div>
          </div>

          <div style="display:flex; flex-direction:row; align-items:center; width:85%; overflow:hidden;">
            <label for='date'>From</label>
            <input type='date' id='startdate' name='startdate' style="margin-left:10px;" required>

            <label style="margin-left:20px;" for='date'>To</label>
            <input type='date' id='enddate' name='enddate' style="margin-left:10px;" required>
          </div>

          <div style="display:flex; flex-direction:row; align-items:center; width:85%;">
            <label for='descr'>Event Description</label>
            <textarea id='descr' name='descr' required></textarea>
          </div>

          <div style="display:flex; flex-direction:row; align-items:center; width:85%; ">
            <label for='location'>Location</label>
            <input style="margin-left:50px;" type="text" id='location' name='location' required></input>
          </div>

          <div style="display:flex;justify-content:space-between;width:80%;">
            <a class="btn-cancel" href="All Profiles.php">Cancel</a>
            <input type='submit' value='Request Booking'>
          </div>
        </form>
      </div>
    </div>
    <div id="confirmation-modal" class="modal" style="display: none;">
      <div class="modal-content">
        <span id="close-modal-btn" class="close">&times;</span>
        <h2>Booking Request Submitted</h2>
        <p><strong>Photographer:</strong> <span id="modal-photographer"></span></p>
        <p><strong>From:</strong> <span id="modal-startdate"></span></p>
        <p><strong>To:</strong> <span id="modal-enddate"></span></p>
        <p><strong>Location:</strong> <span id="modal-location"></span></p>
        <div>
          <p><strong>Description:</strong></p>
          <div class="message-box">
            <span id="modal-description"></span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    function updateTextField(name) {
      document.getElementById('photographer_name').value = name;
    }

    let currentDate = new Date();
    let year = currentDate.getFullYear();
    let month = ("0" + (currentDate.getMonth() + 1)).slice(-2);
    let day = ("0" + currentDate.getDate()).slice(-2);
    let formattedDate = `${year}-${month}-${day}`;
    let startDateInput = document.getElementById("startdate");
    let endDateInput = document.getElementById("enddate");

    startDateInput.setAttribute("min", formattedDate);
    endDateInput.setAttribute("min", formattedDate);

    startDateInput.addEventListener("change", function() {
      let selectedStartDate = this.value;
      endDateInput.setAttribute("min", selectedStartDate);
    });

    endDateInput.addEventListener("change", function() {
      let selectedEndDate = this.value;
    });

    document.getElementById('booking-form').addEventListener('submit', function(event) {
      event.preventDefault();
      let formData = new FormData(this);

      fetch('request.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            showPopup(data.message, data.data);
          } else {
            alert(data.message);
          }
        })
        .catch(error => console.error('Error:', error));
    });

    function showPopup(message, bookingDetails) {
      document.getElementById('modal-photographer').textContent = bookingDetails.photographer;
      document.getElementById('modal-startdate').textContent = bookingDetails.startdate;
      document.getElementById('modal-enddate').textContent = bookingDetails.enddate;
      document.getElementById('modal-description').textContent = bookingDetails.description;
      document.getElementById('modal-location').textContent = bookingDetails.location;
      document.getElementById('confirmation-modal').style.display = 'flex';
    }

    document.getElementById('close-modal-btn').addEventListener('click', function() {
      document.getElementById('confirmation-modal').style.display = 'none';
      window.location.reload();
    });
  </script>
</body>

</html>