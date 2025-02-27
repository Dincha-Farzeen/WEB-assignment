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

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the submitted photographer's name from the POST data
  $photographerName = $_POST['photographer_name'] ?? '';
}else {
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
        body, html {
        margin: 0;
        padding: 0;
        height: 100%;
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
        flex: 1; /* Takes up the remaining space */
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 10px;
        background-color: rgba(101, 67, 33, 0.438); 
        overflow-y: scroll;
        }

        .form-container{
        width: 85%;
        height: 300px;
        margin-top: 30px;
        display: inline-flex;
        align-items: center;
        justify-content: center;

        }
        
        form{
          display: inline-flex;
          align-items: center;
          flex-direction: column;
          width:100%;
        }

        input[type="submit"] {
          width: auto;
          padding: 10px;
          margin-top: 30px;
          font-family: "Lucida Sans", "Lucida Sans Regular", "Lucida Grande",
            "Lucida Sans Unicode", Geneva, Verdana, sans-serif;
          background-color: rgb(101, 67, 33);
          color: white;
          border: none;
          border-radius: 5px;
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
      }
      input[type="text"]:focus,
      input[type="numeric"]:focus, textarea:focus {
        border: 1px solid rgb(116, 116, 116);
        outline: none;
      }
      input[type="text"]:hover,
      textarea:hover,
      input[type="date"]:hover,
      input[type="numeric"]:hover {
        box-shadow: 0 4px 3px rgba(0, 0, 0, 0.1);
      }

      textarea{
        background-color: rgb(219, 216, 216);
        width: 80%;
        height:80px;
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
      a{
        width: auto;
        padding: 5px;
        margin-top: 30px;
        font-family: "Lucida Sans", "Lucida Sans Regular", "Lucida Grande",
          "Lucida Sans Unicode", Geneva, Verdana, sans-serif;
        background-color: rgb(101, 67, 33);
        color: white;
        border: none;
        border-radius: 5px;
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
            min-width: 300px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
            border-radius: 5px;
            padding: 5px;
            margin-top:0px;
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
    
        input[type="text"]{
          width:390px;
        }
        
        .btn-cancel{
          width: auto;
          padding: 10px;
          margin-top: 30px;
          font-family: "Lucida Sans", "Lucida Sans Regular", "Lucida Grande",
            "Lucida Sans Unicode", Geneva, Verdana, sans-serif;
          background-color: rgb(101, 67, 33);
          color: white;
          border: none;
          border-radius: 5px;
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
                <b>E-mail:</b> <?php echo htmlspecialchars($user['u_email']); ?> <br>
                <b>Phone Number:</b> <?php echo htmlspecialchars($user['u_phoneNum']); ?> <br>
                <i>In case of any discrepencies, please update your information<br> 
                and come back again to continue booking.</i>
              </p>
              <a href="myAccount.php">Go to account settings</a></a>
            </div>

            <div class="form-container">
                <form action='submit_booking.php' method='post'>
                    <div class="dropdown">
                      <label for="photographer_name"></label>
                      <input type="text"  id="photographer_name" name="photographer_name" 
                            value="<?php echo htmlspecialchars($photographerName); ?>" placeholder="Photographer" >
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
                  
                    <label for='date'></label>
                    <input type='date' id='date' name='date' >
                  
                    
                    <label for='numberOfdays'></label>
                    <input type='numeric' id='numOfdays' name='numOfdays' placeholder="Number of days of event">
                    
                    <label for='descr'></label>
                    <textarea id='descr' name='descr' placeholder="Event Description"></textarea>

                    <div style="display:flex;justify-content:space-between;width:80%;">
                      <a class="btn-cancel" href="All Profiles.html">Cancel</a>
                      <input type='submit' value='Request Booking'>
                    </div>
                  </form>
                </div>
          </div>
        </div>
        <script>
          function updateTextField(name) {
              document.getElementById('photographer_name').value = name;
          }
        </script>
      </body>
</html>