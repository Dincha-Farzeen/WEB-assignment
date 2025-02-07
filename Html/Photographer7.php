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

$sql = "SELECT image_name
        FROM photographs p JOIN photographer pr
        ON p.photographer_id = pr.photographer_id
        WHERE (pr.name = 'Fransis Davis')";

$stmt = $conn->prepare($sql);
$stmt->execute();
$photos = $stmt->fetchAll(PDO::FETCH_ASSOC);   
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fransis Davis Profile</title>
    <link rel="stylesheet" href="../css/PhotographerCSS.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
<nav class = "navigation-bar">
    <li><a href="homepage.html"> Home </a></li>
    <li><a href="myAccount.php"> My Account </a></li>
    <li><a href="#"> Booking </a></li>
    <li><a href="#"> Reviews </a></li>
    <li> <a href = "All Profiles.html"> Photographers' Profiles </a></li>
</nav>

<div class = "image-profile">
    <img src="../Photographer Images/Photographer 7/P7 profile.jpg" alt="Photo by Doğukan Benli: https://www.pexels.com/photo/photo-of-man-taking-photo-with-dslr-camera-1996759/">
</div>

<h1 class = "heading-name">FRANSIS DAVIS</h1>

<p class="photographer-quote" style="border-left: 5px solid #2F4F4F;">
    <span>"Every event is a tapestry of moments, and my goal is to weave together the 
            highlights that tell your unique story and preserve the joy for years to come."</span>
    <br>
    <b>~ EFransis Davis</b>
</p>

<div class="button-book"><a>BOOK THIS PHOTOGRAPHER</a></div>

<div class="dropdown ">BROWSE PHOTOGRAPHERS</button>
    <div class="dropdown-content">
        <a href="Photographer1.php">Alex Johnson</a>
        <a href="Photographer2.php">Sam Lee</a>
        <a href="Photographer3.php">Olivia Martinez</a>
        <a href="Photographer4.php">Liam Smith</a>
        <a href="Photographer5.php">Emma Davidson</a>
        <a href="Photographer7.php">Sophia Brown</a>
    </div>
</div> 
<br><br><br><br><hr>

<div class="orange-rectangle" style="background-color: rgba(47, 79, 79, 0.5);">
    <div class="heading-review"><h2>What are our customers saying ?</h2></div>  
    <!-- Started adding the first displayed review -->
    <div class="comment1-review"     
    style ="font-size: 21px;
            font-style: italic;
            justify-content: center;
            align-items: center;
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
            text-align: center;
    ">"Amazing experience! Every moment of our celebration was captured beautifully!"
        </div>    <!-- Finished adding the first displayed review -->

    <!-- Started adding the second displayed review -->
    <div class="comment2-review"     
    style ="font-size: 21px;
            font-style: italic;
            justify-content: center;
            align-items: center;
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
            text-align:center;
    ">"The photos from our event are stunning—such a great way to relive the day!"
        </div>   <!-- Finished adding the second displayed review -->
    </div>

<!-- Started adding the third displayed review -->
<div class="comment3-review"     
style ="font-size: 21px;
        font-style: italic;
        justify-content: center;
        align-items: center;
        font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
        text-align:center;
    ">" The images truly reflected our mission and the community's spirit. Highly recommend!"</div>   <!-- Finished adding the third displayed review -->

<div class="photo1-review photo-review"><img src="../Photographer Images/Photographer 7/Clients/Client1.jpg" alt="Photo by Azra  Tuba Demir: https://www.pexels.com/photo/traditional-folk-dance-performance-indoors-28858983/"></div>
<div class="photo2-review photo-review"><img src="../Photographer Images/Photographer 7/Clients/Client2.jpg" alt="Photo by Joel  Alencar : https://www.pexels.com/photo/a-man-putting-food-in-a-container-at-a-food-stall-25309435/"></div>
<div class="photo3-review photo-review"><img src="../Photographer Images/Photographer 7/Clients/Client3.jpg" alt="Photo by Lara Jameson: https://www.pexels.com/photo/people-protesting-near-the-water-fountain-8899203/"></div>
<hr>

<h3 style ="font-family:Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
            position: absolute; /* Position overlay absolutely */
            font-size: 31px;
            margin: 10px 0 0 90px;
            font-family:Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
            margin-left: 60px;
            color: black;
";>Featured Images</h3>

<div style="margin-top: 80px; display: flex; justify-content:center; gap: 20px;flex-wrap:wrap; ">
    <?php foreach ($photos as $photo): ?>
        <div class = "gallery">
            <img src="../Photographer Images/Photographer 7/<?php echo htmlspecialchars($photo['image_name']); ?>">
        </div>
    <?php endforeach; ?>
</div>
<div style="font-style: italic;
            justify-content: center;
            display: flex;
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif; 
            font-size: 20px;
            margin-top: 50px;
">More Images Coming Soon...</div>

<footer>
    <div style=" font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
                    font-size: 20px;
                    letter-spacing: 1px;
                    margin-left: 40px;
    "><b>STAY CONNECTED TO US</b></div>
    <div class="social-icons" style="text-align: center; margin-top: 10px; margin-left: -1170px;">
        <a href="https://www.facebook.com" target="_blank" style="color: white; margin: 0 5px; font-size: 30px;">
            <i class="fab fa-facebook"></i>
        </a>
    </div> 

    <div class="social-icons" style="text-align: center; margin-top: -34px; margin-left: -1070px;">
        <a href="https://www.instagram.com" target="_blank" style="color: white; margin: 0 5px; font-size: 33px;">
            <i class="fab fa-instagram"></i>
        </a>
    </div> 

    <div class="social-icons" style="text-align: center; margin-top: -36px; margin-left: -960px;">
        <a href="https://www.youtube.com" target="_blank" style="color: white; margin: 0 5px; font-size: 32px;">
            <i class="fab fa-youtube"></i>
        </a>
    </div> 


    <div class="footer-links" style="margin: -22px 0 0 885px; font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif; font-size: 19px;">
        <a href="contact.html" style="color: white; text-decoration: none;">Contact Us</a>
    </div>
    
    <div class="footer-links" style="margin: -22px 0 0 1000px; font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif; font-size: 19px;">
        <a href="about.html" style="color: white; text-decoration: none;">About Us</a>
    </div>
    
    <div class="footer-links" style="margin: -22px 0 0 1100px; font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif; font-size: 19px;">
        <a href="termsAndCond.html" style="color: white; text-decoration: none;">Terms and Conditions</a>
    </div>
    


    <div class="footer-reservedtext">&copy; 2024 Photography Collective. All rights reserved.</div>

</footer>
</body>
</html> 
