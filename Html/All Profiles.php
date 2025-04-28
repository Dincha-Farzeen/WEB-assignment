<?php

$dsn = "mysql:host=localhost;dbname=photography_collective";
$username = 'root';
$password = '';

try {
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$sql = "SELECT pr.name, photographer_id, bio
        FROM  photographer pr";

$stmt = $conn->prepare($sql);
$stmt->execute();
$photographers = $stmt->fetchAll(PDO::FETCH_ASSOC);   

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>All Photographers Profiles</title>
    <link rel="stylesheet" href="../css/AllProfilesCSS.css" />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/icon?family=Material+Icons"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    />
  </head>

  <body>
    <nav class="navigation-bar">
      <li><a href="homepage.html"> Home </a></li>
      <li><a href="myAccount.php"> My Account </a></li>
      <li><a href="booking.php"> Booking </a></li>
      <li><a href="review.php"> Reviews </a></li>
    </nav>

    <div class="container">
      <div
        style="
          font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;
          display: flex;
          font-size: 60px;
          margin: 40px 0 0 170px;
          letter-spacing: 4px;
        "
      >
        MEET OUR TEAM OF<br />PHOTOGRAPHERS
      </div>

      <div class="team-image">
        <img
          src="../Photographer Images/All Photographers/AP1.jpg"
          alt="Photo by Brett Sayles: https://www.pexels.com/photo/people-taking-photos-2479946/"
        />
      </div>
      <div class="team-image2">
        <img
          src="../Photographer Images/All Photographers/AP2.jpg"
          alt="Photo by Matheus Bertelli: https://www.pexels.com/photo/a-woman-taking-photo-using-dslr-camera-12802262/"
        />
      </div>
      <div class="team-image3">
        <img
          src="../Photographer Images/All Photographers/AP3.jpg"
          alt="Photo by KoolShooters  : https://www.pexels.com/photo/photographers-working-in-studio-7322582/"
        />
      </div>
      <div
        style="
          margin: -200px 0 0 200px;
          text-indent: 30px;
          word-spacing: 4px;
          font-style: italic;
        "
      >
        Discover our talented photography team dedicated to<br />
        capturing your most cherished moments. Each photographer<br />
        brings a unique style, ensuring every shot tells a captivating
        <br />story. Explore their portfolios and find the perfect match<br />
        for your event. Let's create unforgettable memories together!
      </div>

      <div style="margin: 50px 0 0 280px">
        <a class="contact-button" href="contact.html">Get In Contact With Us</a>
      </div>
    </div>
    <br /><br /><br /><br /><br />
    
    <?php foreach ($photographers as $photographer): ?>
      <hr>
      <div
        style="
          width: 150px;
          height: 10px;
          display: flex;
          margin: 60px 0 0 175px;
          background-color: burlywood;
        "
      ></div>
      <div
        style="
          font-size: 60px;
          font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;
          letter-spacing: 9px;
          margin: 10px 0 0 180px;
        "
      >
      <?php echo htmlspecialchars($photographer['name']); ?>
      </div>

      <div
        style="
          font-size: 20px;
          font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
          margin: 10px 0 0 180px;
          text-indent: 70px;
          display: flex-box;
          width:50%;
          overflow:flex;
        "
      >
      <?php echo htmlspecialchars($photographer['bio']); ?>
      </div>

      <div style="margin: -80px 0 0 940px">
        <a class="contact-button" href="Photographer<?php echo htmlspecialchars($photographer['photographer_id']); ?>.php"
          >Visit Photographer's Profile</a
        >
      </div>
      <br /><br /><br /><br /><br /><br /><br />
     
    <?php endforeach; ?>
    

    <footer>
      <div
        style="
          font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
          font-size: 20px;
          letter-spacing: 1px;
          margin-left: 40px;
        "
      >
        <b>STAY CONNECTED TO US</b>
      </div>
      <div
        class="social-icons"
        style="text-align: center; margin-top: 10px; margin-left: -1170px"
      >
        <a
          href="https://www.facebook.com"
          target="_blank"
          style="color: white; margin: 0 5px; font-size: 30px"
        >
          <i class="fab fa-facebook"></i>
        </a>
      </div>

      <div
        class="social-icons"
        style="text-align: center; margin-top: -34px; margin-left: -1070px"
      >
        <a
          href="https://www.instagram.com"
          target="_blank"
          style="color: white; margin: 0 5px; font-size: 33px"
        >
          <i class="fab fa-instagram"></i>
        </a>
      </div>

      <div
        class="social-icons"
        style="text-align: center; margin-top: -36px; margin-left: -960px"
      >
        <a
          href="https://www.youtube.com"
          target="_blank"
          style="color: white; margin: 0 5px; font-size: 32px"
        >
          <i class="fab fa-youtube"></i>
        </a>
      </div>

      <div>
        <a
          class="footer-links"
          href="about.html"
          style="
            margin: -22px 0 0 980px;
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman',
              serif;
            text-decoration: none;
            color: white;
            font-size: 19px;
          "
          >About Us</a
        >
      </div>

      <div
        class="footer-links"
        href="termsAndCond.html"
        style="
          margin: -22px 0 0 1100px;
          font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
          font-size: 19px;
        "
      >
        Terms and Conditions
      </div>

      <div class="footer-reservedtext">
        &copy; 2024 Photography Collective. All rights reserved.
      </div>
    </footer>
  </body>
</html>
