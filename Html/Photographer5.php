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
        WHERE (pr.photographer_id = 5)";

$stmt = $conn->prepare($sql);
$stmt->execute();
$name = $stmt->fetch(PDO::FETCH_ASSOC);

$sql = "SELECT image_name
        FROM photographs p JOIN photographer pr
        ON p.photographer_id = pr.photographer_id
        WHERE (pr.photographer_id = 5)";

$stmt = $conn->prepare($sql);
$stmt->execute();
$photos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emma Davidson Profile</title>
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
        <img src="../Photographer Images/Photographer 5/P5 profile.jpg" alt="Photo by Ralph Rabago: https://www.pexels.com/photo/woman-sitting-down-looking-into-a-dslr-camera-3214692/">
    </div>

    <h1 class="heading-name"><?php echo htmlspecialchars($name['name']); ?></h1>

    <p class="photographer-quote" style="border-left: 5px solid #004225;">
        <span>"Through my lens, I see the heartbeat of every event. It's not just about
            the grand moments but the quiet smiles and the laughter that bring life to the occasion."</span>
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
            <a href="Photographer6.php">Sophia Brown</a>
            <a href="Photographer7.php">Fransis Davis</a>
        </div>
    </div>
    <br><br><br><br>
    <hr>

    <div class="orange-rectangle" style="background-color: rgba(0, 66, 37, 0.5);">
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
    ">"Captured our corporate event flawlessly! The pictures speak volumes."
        </div> <!-- Finished adding the first displayed review -->

        <!-- Started adding the second displayed review -->
        <div class="comment2-review"
            style="font-size: 21px;
            font-style: italic;
            justify-content: center;
            align-items: center;
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
            text-align:center;
    ">"The energy and joy from our anniversary were perfectly captured. Thank you!"
        </div> <!-- Finished adding the second displayed review -->
    </div>

    <!-- Started adding the third displayed review -->
    <div class="comment3-review"
        style="font-size: 21px;
        font-style: italic;
        justify-content: center;
        align-items: center;
        font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
        text-align:center;
    ">"Amazing shots from our charity event, couldn't have asked for better!"</div> <!-- Finished adding the third displayed review -->

    <div class="photo1-review photo-review"><img src="../Photographer Images/Photographer 5/Clients/Client1.jpg" alt="Photo by Matheus Bertelli: https://www.pexels.com/photo/photo-of-man-sitting-in-front-3321795/"></div>
    <div class="photo2-review photo-review"><img src="../Photographer Images/Photographer 5/Clients/Client2.jpg" alt="Photo by Ivan Samkov: https://www.pexels.com/photo/children-wearing-party-hats-sitting-on-sofa-chair-8101649/"></div>
    <div class="photo3-review photo-review"><img src="../Photographer Images/Photographer 5/Clients/Client3.jpg" alt="Photo by RDNE Stock project: https://www.pexels.com/photo/man-and-woman-carrying-medicine-and-food-labelled-cardboard-boxes-behind-a-white-van-6646907/"></div>
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
                <img src="../Photographer Images/Photographer 5/<?php echo htmlspecialchars($photo['image_name']); ?>">
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