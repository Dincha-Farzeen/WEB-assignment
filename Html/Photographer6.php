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
        WHERE (pr.photographer_id = 6)";

$stmt = $conn->prepare($sql);
$stmt->execute();
$name = $stmt->fetch(PDO::FETCH_ASSOC);

$sql = "SELECT image_name
        FROM photographs p JOIN photographer pr
        ON p.photographer_id = pr.photographer_id
        WHERE (pr.photographer_id = 6)";

$stmt = $conn->prepare($sql);
$stmt->execute();
$photos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sophia Brown Profile</title>
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
    <nav class="navigation-bar">
        <li><a href="homepage.html"> Home </a></li>
        <li><a href="myAccount.php"> My Account </a></li>
        <li><a href="booking.php"> Booking </a></li>
        <li><a href="review.php"> Reviews </a></li>
        <li> <a href="All Profiles.php"> Photographers' Profiles </a></li>
    </nav>

    <div class="image-profile">
        <img src="../Photographer Images/Photographer 6/p6 profile.jpg" alt="Photo by Bia Sousa: https://www.pexels.com/photo/woman-taking-picture-2191055/">
    </div>

    <h1 class="heading-name"><?php echo htmlspecialchars($name['name']); ?></h1>

    <p class="photographer-quote" style="border-left: 5px solid #B22222;">
        <span>"A wedding or birthday is a fleeting moment, but photography makes
            it timeless, capturing every laugh, tear, and embrace in a way words never could"</span>
        <br>
        <b>~ <?php echo htmlspecialchars($name['name']); ?></b>
    </p>

    <form action="booking.php" method="post">
        <input type="hidden" name="photographer_name" value="<?php echo htmlspecialchars($name['name']); ?>">
        <input type="submit" value="BOOK THIS PHOTOGRAPHER">
    </form>

    <div class="dropdown ">BROWSE PHOTOGRAPHERS</button>
        <div class="dropdown-content">
            <a href="Photographer1.php">Alex Johnson</a>
            <a href="Photographer2.php">Sam Lee</a>
            <a href="Photographer3.php">Olivia Martinez</a>
            <a href="Photographer4.php">Liam Smith</a>
            <a href="Photographer5.php">Emma Davidson</a>
            <a href="Photographer7.php">Fransis Davis</a>
        </div>
    </div>
    <br><br><br><br>
    <hr>

    <div class="orange-rectangle" style="background-color: rgba(178, 34, 34, 0.5);">
        <div class="heading-review">
            <h2>What are our customers saying ?</h2>
        </div>
        <!-- Started adding the first displayed review -->
        <div class="comment1-review"
            style="font-size: 21px;
            font-style: italic;
            justify-content: center;
            align-items: center;
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
            text-align: center;
    ">"The birthday pictures were flawless, exactly what we wanted!"</div> <!-- Finished adding the first displayed review -->

        <!-- Started adding the second displayed review -->
        <div class="comment2-review"
            style="font-size: 21px;
            font-style: italic;
            justify-content: center;
            align-items: center;
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
            text-align:center;
    ">"Our wedding memories were brought to life through these amazing shots!"</div> <!-- Finished adding the second displayed review -->
    </div>

    <!-- Started adding the third displayed review -->
    <div class="comment3-review"
        style="font-size: 21px;
        font-style: italic;
        justify-content: center;
        align-items: center;
        font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
        text-align:center;
    ">"The wedding photos are simply stunning. Every detail captured perfectly!"
    </div> <!-- Finished adding the third displayed review -->

    <div class="photo1-review photo-review"><img src="../Photographer Images/Photographer 6/Clients/Client1.jpg" alt="Photo by Nataliya Vaitkevich: https://www.pexels.com/photo/woman-celebrating-her-50th-birthday-7826351/"></div>
    <div class="photo2-review photo-review"><img src="../Photographer Images/Photographer 6/Clients/Client2.jpg" alt="Photo by Eko Agalarov: https://www.pexels.com/photo/elegant-outdoor-wedding-with-bride-and-groom-28815668/"></div>
    <div class="photo3-review photo-review"><img src="../Photographer Images/Photographer 6/Clients/Client3.jpg" alt="Photo by Alexander Mass: https://www.pexels.com/photo/newlyweds-together-at-wedding-19372846/"></div>
    <hr>

    <h3 style="font-family:Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
            position: absolute; /* Position overlay absolutely */
            font-size: 31px;
            margin: 10px 0 0 90px;
            font-family:Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
            margin-left: 60px;
            color: black;
" ;>Featured Images</h3>
    <div style="margin-top: 80px; display: flex; justify-content:center; gap: 20px;flex-wrap:wrap; ">
        <?php foreach ($photos as $photo): ?>
            <div class="gallery">
                <img src="../Photographer Images/Photographer 6/<?php echo htmlspecialchars($photo['image_name']); ?>">
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

    <footer class="footer">
        <div>
            <div style="padding-left: 50px">CONNECT WITH US</div>
            <div class="socials">
                <div>
                    <a
                        href="https://www.facebook.com"
                        target="_blank"
                        style="color: white; margin: 0 5px; font-size: 30px">
                        <i class="fab fa-facebook"></i>
                    </a>
                </div>
                <div>
                    <a
                        href="https://www.instagram.com"
                        target="_blank"
                        style="color: white; margin: 0 5px; font-size: 33px">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>
                <div>
                    <a
                        href="https://www.youtube.com"
                        target="_blank"
                        style="color: white; margin: 0 5px; font-size: 32px">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="navigations">
            <div>NAVIGATIONS</div>
            <div style="margin-top: 5px">
                <a href="about.html" style="text-decoration: none; color: white">About us</a>
            </div>
            <div style="margin-top: 5px">
                <a href="contact.html" style="text-decoration: none; color: white">Contact Us</a>
            </div>
            <div style="margin-top: 5px">
                <a href="termandcond.html" style="text-decoration: none; color: white">Terms and Conditions</a>
            </div>
        </div>
        <div style="display: flex; justify-content: flex-end; width: 70%">
            &copy; 2024 Photography Collective. All rights reserved
        </div>
    </footer>
</body>

</html>