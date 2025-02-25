<?php
try {
    // Database connection details
    $dsn = "mysql:host=localhost;dbname=photography_collective;charset=utf8mb4";
    $dbusername = "root";
    $dbpassword = "";

    // Create a new PDO instance
    $pdo = new PDO($dsn, $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare and execute the SQL statement
    $sql = "SELECT u_name FROM registered_user ORDER BY u_name ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Log the error and show a generic message
    error_log("Database error: " . $e->getMessage());
    $error = "database_error";
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
        rel="stylesheet"
        href="https://fonts.googleapis.com/icon?family=Material+Icons" />
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <title>Reviews</title>
</head>
<style>body,
html {
  margin: 0;
  padding: 0;
  font-family: Arial, Helvetica, sans-serif;
}

nav {
  justify-content: space-evenly;
  display: flex;
  margin-bottom: 10px;
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

nav a:hover {
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
}

.reviewactive {
  background-color: rgb(232, 230, 230);
}

.text-area {
  width: 60%;
  display: flex;
  flex-direction: column;
}

.image-area {
  width: 40%;
  height: 500px;
  background-image: url("../Html/review.png");
  background-repeat: no-repeat;
  background-size: contain;
}

.heading {
  font-family: Impact, Haettenschweiler, "Arial Narrow Bold", sans-serif;
  font-size: 70px;
  letter-spacing: 7px;
  margin-left: 100px;
  margin-top: 60px;
}

.paragraph {
  margin-left: 100px;
  width: 600px;
  margin-top: -10px;
  font-size: 18px;
}

.review-box {
  width: 230px;
  height: 180px;
  background-color: rgb(252, 250, 242);
  flex-direction: column;
  padding: 10px;
  border-radius: 10px;
  box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.09);
}

.material-icons {
  color: gold;
  display: inline-flex;
}

.star-box {
  width: 330px;
  height: 180px;
  background-color: rgb(252, 250, 242);
  display: flex;
  flex-direction: column;
  padding: 10px 10px 10px 40px;
  border-radius: 10px;
  box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.09);
  justify-content: center;
  align-items: left;
}

.star-review {
  text-align: left;
}

.review-container {
  width: 1200px;
  height: 500px;
  margin: 30px auto;
  background-color: ghostwhite;
  display: flex;
  border-radius: 20px;
  padding: 20px;
  box-shadow: inset 1px 1px 7px rgba(0, 0, 0, 0.2);
}

.dropdown {
  background-color: #c3c3e9;
  border: none;
  padding: 10px 16px;
  font-family: Cambria, Cochin, Georgia, Times, "Times New Roman", serif;
  border-radius: 10px;
  text-align: center;
  transition: 0.5s ease-out;
}

.dropdown:hover {
  /*Customize dropdown when hovering on the box*/
  background-color: #b6b6d8;
  box-shadow: 0 2px 7px rgba(0, 0, 0, 0.3);
}

.dropdown-content {
  /*Customize contents being displayed when hovering*/
  display: none;
  position: absolute;
  background-color: #f9f9f9;
  width: 200px;
  margin-top: 8px;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  z-index: 6;
}

.dropdown-content a {
  /*Customize the content of the drop down*/
  color: black;
  padding: 10px 16px;
  text-decoration: none;
  display: block;
  border-radius: 5px;
}

.dropdown-content a:hover {
  /*Customize when hovering over the drop down content*/
  background-color: #c4c4ee;
  box-shadow: 0 2px 7px rgba(0, 0, 0, 0.3);
}

.dropdown:hover .dropdown-content {
  /* Show the dropdown menu on hover */
  display: block;
}
</style>
<body>
    <nav>
        <a class="homeactive">Home</a>
        <a href="booking.php">Book Now</a>
        <a class="reviewactive" href="review.php">Reviews</a>
        <a href="about.html">About Us</a>
        <a href="login.php">Log In/Sign Up</a>
        <a href="myAccount.php">My Profile</a>
    </nav>
    <div style="display: flex; flex-direction: row">
        <div class="text-area">
            <h1 class="heading">LET'S HEAR FROM OUR CUSTOMERS</h1>
            <p class="paragraph">
                We value the feedback and experiences of our customers. Your reviews
                help us improve and ensure we provide the best service possible. Take
                a moment to read what others have shared, and feel free to leave your
                thoughts as well!
            </p>
        </div>
        <div class="image-area"></div>
    </div>
    <div
        style="display: flex; flex-direction: row; justify-content: space-evenly">
        <div class="review-box">
            <p style="font-weight: 700; font-size: 20px; margin-left: 10px">
                Reviews
            </p>
            <div>
                <div style="text-align: center; margin-top: 20px">
                    <p
                        style="
                font-size: 60px;
                margin-top: -10px;
                font-family: Impact, Haettenschweiler, 'Arial Narrow Bold',
                  sans-serif;
                display: inline;
              ">
                        4.5
                    </p>
                    <p style="display: inline">out of 5</p>
                </div>
                <div style="text-align: center">
                    <span class="material-icons" style="font-size: 25px">star</span>
                    <span class="material-icons" style="font-size: 25px">star</span>
                    <span class="material-icons" style="font-size: 25px">star</span>
                    <span class="material-icons" style="font-size: 25px">star</span>
                    <span class="material-icons" style="font-size: 25px">star_half</span>
                </div>
            </div>
        </div>
        <div class="star-box">
            <div class="star-review">
                <p style="display: inline; font-size: 25px">5</p>
                <span
                    class="material-icons"
                    style="font-size: 20px; margin-left: 10px">star</span>
                <span class="material-icons" style="font-size: 20px">star</span>
                <span class="material-icons" style="font-size: 20px">star</span>
                <span class="material-icons" style="font-size: 20px">star</span>
                <span class="material-icons" style="font-size: 20px">star</span>
                <p style="display: inline">
                    <b style="font-size: 20px; margin-left: 13px">70%</b> (90 reviews)
                </p>
            </div>

            <div class="star-review">
                <p style="display: inline; font-size: 25px">4</p>
                <span
                    class="material-icons"
                    style="font-size: 20px; margin-left: 10px">star</span>
                <span class="material-icons" style="font-size: 20px">star</span>
                <span class="material-icons" style="font-size: 20px">star</span>
                <span class="material-icons" style="font-size: 20px">star</span>
                <p style="display: inline">
                    <b style="font-size: 20px; margin-left: 13px">15%</b> (18 reviews)
                </p>
            </div>

            <div class="star-review">
                <p style="display: inline; font-size: 25px">3</p>
                <span
                    class="material-icons"
                    style="font-size: 20px; margin-left: 10px">star</span>
                <span class="material-icons" style="font-size: 20px">star</span>
                <span class="material-icons" style="font-size: 20px">star</span>
                <p style="display: inline">
                    <b style="font-size: 20px; margin-left: 13px">5%</b> (6 reviews)
                </p>
            </div>

            <div class="star-review">
                <p style="display: inline; font-size: 25px">2</p>
                <span
                    class="material-icons"
                    style="font-size: 20px; margin-left: 10px">star</span>
                <span class="material-icons" style="font-size: 20px">star</span>
                <p style="display: inline">
                    <b style="font-size: 20px; margin-left: 13px">3%</b> (4 reviews)
                </p>
            </div>

            <div class="star-review">
                <p style="display: inline; font-size: 25px">1</p>
                <span
                    class="material-icons"
                    style="font-size: 20px; margin-left: 10px">star</span>
                <p style="display: inline">
                    <b style="font-size: 20px; margin-left: 13px">2%</b> (2 reviews)
                </p>
            </div>
        </div>
    </div>
    <div class="review-container">
        <div style="width: 300px; height: 100px; background-color: aqua; display: flex; flex-direction: row;">
            <div>FILTER REVIEWS</div>
            <div class="dropdown ">BROWSE PHOTOGRAPHERS</div>
            <div class="dropdown-content">
                <a href="Photographer1.html">Alex Johnson</a>
                <a href="Photographer2.html">Sam Lee</a>
                <a href="Photographer3.html">Olivia Martinez</a>
                <a href="Photographer4.html">Liam Smith</a>
                <a href="Photographer5.html">Emma Davidson</a>
                <a href="Photographer7.html">Sophia Brown</a>
            </div>
        </div>
    </div>
    <br><br><br><br>
</body>

</html>