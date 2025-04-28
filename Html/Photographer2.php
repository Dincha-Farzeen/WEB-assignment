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

$sql = "SELECT pr.name
        FROM  photographer pr
        WHERE (pr.photographer_id = 2)";

$stmt = $conn->prepare($sql);
$stmt->execute();
$name = $stmt->fetch(PDO::FETCH_ASSOC);

$sql = "SELECT image_name
        FROM photographs p JOIN photographer pr
        ON p.photographer_id = pr.photographer_id
        WHERE (pr.photographer_id = 2)";

$stmt = $conn->prepare($sql);
$stmt->execute();
$photos = $stmt->fetchAll(PDO::FETCH_ASSOC); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sam Lee Profile</title>
    <link rel="stylesheet" href="../css/PhotographerCSS.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        input[type="submit"] {
            font-size: 15px;
            border: none;
            position: absolute;
            background-color: #c3c3e9;
            margin: 10px;
            padding: 10px 16px;
            font-family: Cambria, Cochin, Georgia, Times, "Times New Roman", serif;
            border-radius: 10px;
            text-align: center;
            transition: 0.5s ease-out;
            margin: 36px 0 0 190px;
        }
        input[type="submit"]:hover {
            /*Customize button when hovering on the box*/
            background-color: #b6b6d8;
            transform: scale(1.001);
            box-shadow: 0 2px 7px rgba(0, 0, 0, 0.3);
        }

        input[type="submit"]:active {
            /*Customize button when clicking on the box*/
            background-color: #b6b6d8;
        }
    </style>
</head>

<body>
<nav class = "navigation-bar">
    <li><a href="homepage.html"> Home </a></li>
    <li><a href="myAccount.php"> My Account </a></li>
    <li><a href="#"> Booking </a></li>
    <li><a href="#"> Reviews </a></li>
    <li> <a href = "All Profiles.php"> Photographers' Profiles </a></li>
</nav>

<div class = "image-profile">
    <img src="../Photographer Images/Photographer 2/p2 profile.jpg" alt="Photo by Fady Hany from Pexels: https://www.pexels.com/photo/man-holding-black-canon-dslr-camera-1466845/">
</div>

<h1 class = "heading-name"><?php echo htmlspecialchars($name['name']); ?></h1>

<p class="photographer-quote" style="border-left: 5px solid #008080;">
    <span>"You don't just capture a moment with a camera; you weave together the memories 
        you've experienced, the emotions you've felt, the stories you've heard, and the beauty 
        you've seen into every shot."</span>
    <br>
    <b>~ <?php echo htmlspecialchars($name['name']); ?></b>
</p>

<form action="booking.php" method="post" >
        <input type="hidden" name="photographer_name" value="<?php echo htmlspecialchars($name['name']); ?>">
        <input type="submit" value="BOOK THIS PHOTOGRAPHER">
    </form>

<div class="dropdown ">BROWSE PHOTOGRAPHERS</button>
    <div class="dropdown-content">
        <a href="Photographer1.php">Alex Johnson</a>
        <a href="Photographer3.php">Olivia Martinez</a>
        <a href="Photographer4.php">Liam Smith</a>
        <a href="Photographer5.php">Emma Davidson</a>
        <a href="Photographer6.php">Sophia Brown</a>
        <a href="Photographer7.php">Fransis Davis</a>
    </div>
</div> 
<br><br><br><br><hr>

<div class="orange-rectangle" style="background-color: rgba(0, 128, 128, 0.5);">
    <div class="heading-review"><h2>What are our customers saying ?</h2></div>  
    <!-- Started adding the first displayed review -->
    <div class="comment1-review"     
    style ="font-size: 21px;
            font-style: italic;
            justify-content: center;
            align-items: center;
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
            text-align: center;
    ">"Awesome birthday shoot with Sam Lee! Every moment was captured beautifully. 
        Highly recommend!"</div>    <!-- Finished adding the first displayed review -->

    <!-- Started adding the second displayed review -->
    <div class="comment2-review"     
    style ="font-size: 21px;
            font-style: italic;
            justify-content: center;
            align-items: center;
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
            text-align:center;
    ">"Thank you Sam for capturing this moment for me. 
        This will make a good memory for me!"</div>   <!-- Finished adding the second displayed review -->
</div>

<!-- Started adding the third displayed review -->
<div class="comment3-review"     
style ="font-size: 21px;
        font-style: italic;
        justify-content: center;
        align-items: center;
        font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
        text-align:center;
    ">"Had an amazing engagement shoot with Sam! He perfectly captured our love."</div>   <!-- Finished adding the third displayed review -->

<div class="photo1-review photo-review"><img src="../Photographer Images/Photographer 2/Clients/Client1.jpg" alt="Photo by Rodrigo Souza: https://www.pexels.com/photo/pink-three-layered-cake-2531546/"></div>
<div class="photo2-review photo-review"><img src="../Photographer Images/Photographer 2/Clients/Client2.jpg" alt="Photo by Joy Anne Pura: https://www.pexels.com/photo/shallow-focus-photography-of-person-wearing-multicolored-costume-1186116/"></div>
<div class="photo3-review photo-review"><img src="../Photographer Images/Photographer 2/Clients/Client3.jpg" alt="Photo by Rocsana Nicoleta Gurza: https://www.pexels.com/photo/man-and-woman-kissing-948185/"></div>
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
            <img src="../Photographer Images/Photographer 2/<?php echo htmlspecialchars($photo['image_name']); ?>">
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
