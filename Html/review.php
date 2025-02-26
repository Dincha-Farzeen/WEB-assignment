<?php
session_start(); // Ensure session is started

try {
    // Database connection 
    $dsn = "mysql:host=localhost;dbname=photography_collective;charset=utf8mb4";
    $dbusername = "root";
    $dbpassword = "";

    // Create a new PDO instance
    $pdo = new PDO($dsn, $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Initialize variables
    $avg_rating = 0.0; // Default value
    $ratings = []; // Default value

    // Handle form submission for leaving a review
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rating'], $_POST['comment'])) {
        $rating = $_POST['rating'];
        $comment = $_POST['comment'];
        $userId = $_SESSION['user_id']; // Assuming user ID is stored in session

        // Fetch the user name based on the user ID
        $userQuery = $pdo->prepare("SELECT u_name FROM registered_user WHERE u_id = ?");
        $userQuery->execute([$userId]);
        $user = $userQuery->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $date = date('Y-m-d H:i:s'); // Get the current date and time

            // Insert the review into the reviews table
            $insertQuery = $pdo->prepare("INSERT INTO reviews (rating, date, comment, u_id) VALUES (?, ?, ?, ?)");
            $insertQuery->execute([$rating, $date, $comment, $userId]);

            // Redirect to the same page to avoid form resubmission
            header("Location: review.php");
            exit();
        } else {
            $error = "User not found.";
        }
    }

    // Fetch reviews and calculate average rating
    $orderBy = "date ASC";
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $selectedFilter = $_POST['sortReview'] ?? 'oldest';
        if ($selectedFilter === 'newest') {
            $orderBy = "date DESC";
        }
    }

    // Fetch reviews with user names
    $sql = "SELECT r.rating, r.date, r.comment, u.u_name 
            FROM reviews r 
            JOIN registered_user u ON r.u_id = u.u_id 
            ORDER BY $orderBy";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get total number of reviews
    $totalQuery = $pdo->query("SELECT COUNT(*) AS total_reviews FROM reviews");
    $total_reviews = $totalQuery->fetch(PDO::FETCH_ASSOC)['total_reviews'];

    // Get average rating
    $avgQuery = $pdo->query("SELECT AVG(rating) AS avg_rating FROM reviews");
    if ($avgQuery) {
        $avg_rating = number_format($avgQuery->fetch(PDO::FETCH_ASSOC)['avg_rating'], 1);
    }

    // Get star rating distribution and percentages
    $ratingQuery = $pdo->query("
        SELECT rating, COUNT(*) AS count, 
               (COUNT(*) * 100 / (SELECT COUNT(*) FROM reviews)) AS percentage 
        FROM reviews GROUP BY rating ORDER BY rating DESC
    ");
    while ($row = $ratingQuery->fetch(PDO::FETCH_ASSOC)) {
        $ratings[$row['rating']] = [
            'count' => $row['count'],
            'percentage' => number_format($row['percentage'], 2) . '%'
        ];
    }
} catch (PDOException $e) {
    // Log the error and show a detailed message
    error_log("Database error: " . $e->getMessage());
    $error = "Database error: " . $e->getMessage();
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
<style>
    body,
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
        width: 380px;
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
        flex-direction: column;
        border-radius: 20px;
        padding: 20px;
        box-shadow: inset 1px 1px 7px rgba(0, 0, 0, 0.2);
    }

    .filter-container {
        width: 430px;
        height: 70px;
        display: inline-flex;
        flex-direction: row;
        gap: 20px;
        align-items: center;
        justify-content: flex-start;
        padding: 10px;
        margin-left: auto;
    }

    .filter-container div {
        font-size: 20px;
    }

    .dropdown {
        padding: 8px;
        font-size: 16px;
        background-color: rgb(252, 250, 242);
        border: 1px solid #ddd;
        border-radius: 10px;
        cursor: pointer;
        height: 35px;
        margin-right: 10px;
    }

    input[type="submit"] {
        padding: 0px 13px;
        background-color: rgb(252, 250, 242);
        color: black;
        border: none;
        border-radius: 20px;
        cursor: pointer;
        border: 1px solid #ddd;
        font-size: 15px;
        height: 40px;
        margin-top: 0;
        transition: all 0.7s ease-in-out;
    }

    input[type="submit"]:hover {
        background-color: rgb(240, 238, 231);
    }

    .display-review {
        width: auto;
        max-height: 500px;
        overflow-y: auto;
        margin: 0px 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
    }

    .reviews {
        width: 1000px;
        height: auto;
        padding: 20px 0px;
        margin: 10px 0px;
        border-radius: 20px;
        background-color: rgb(251, 249, 244);
        display: flex;
        align-items: center;
        justify-content: space-evenly;
        flex-direction: row;
    }

    .material-icons {
        color: gold;
        display: inline-block;
        vertical-align: middle;
        margin-bottom: 7px;
    }

    .comment-section {
        width: 500px;
        height: auto;
    }

    .submitreview-container {
        width: 1200px;
        height: 400px;
        display: flex;
        flex-direction: row;
        justify-self: center;
        align-items: center;
    }

    .leavereview-container {
        width: 500px;
        height: 300px;
        background-color: rgb(252, 250, 242);
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 20px;
        border-radius: 20px;
        box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.09);
        margin: 50px 50px;
        gap: 10px;
    }

    .footer {
        width: 100%;
        margin-top: 10px;
        height: 130px;
        background-color: black;
        display: flex;
        flex-direction: row;
        color: white;
        align-items: center;
    }

    .socials {
        margin-top: 5px;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
        padding-left: 50px;
        gap: 2px;
    }

    .navigations {
        padding-left: 50px;
        display: flex;
        flex-direction: column;
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
                        <?php echo $avg_rating; ?>
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
            <?php for ($i = 5; $i >= 1; $i--): ?>
                <div class="star-review">
                    <p style="display: inline; font-size: 25px"><?php echo $i; ?></p>
                    <?php for ($j = 1; $j <= $i; $j++): ?>
                        <span class="material-icons" style="font-size: 20px; margin-left: 10px">star</span>
                    <?php endfor; ?>
                    <p style="display: inline">
                        <b style="font-size: 20px; margin-left: 13px">
                            <?php echo $ratings[$i]['percentage'] ?? '0.00%'; ?>
                        </b>
                        (<?php echo $ratings[$i]['count'] ?? 0; ?> reviews)
                    </p>
                </div>
            <?php endfor; ?>
        </div>

    </div>
    <div class="review-container">
        <div class="filter-container">
            <div style="font-size: 20px;">FILTER REVIEWS</div>
            <form action="" method="POST">
                <select name="sortReview" class="dropdown">
                    <option value="oldest" class="dropdown-option">Oldest First</option>
                    <option value="newest" class="dropdown-option">Newest First</option>
                </select>
                <input type="submit" value="Sort">
            </form>
        </div>
        <div class="display-review">
            <?php if (isset($error)): ?>
                <p style="color: red; font-weight: bold;"><?php echo $error; ?></p>
            <?php elseif (isset($reviews) && count($reviews) > 0): ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="reviews">
                        <div style="display: flex; flex-direction: column; gap: 7px">
                            <div><b>Name:</b> <?php echo htmlspecialchars($review['u_name']); ?></div>
                            <div><b>Date:</b> <?php echo htmlspecialchars($review['date']); ?></div>
                            <div><b>Review:</b><?php echo str_repeat('<span class="material-icons">star</span>', htmlspecialchars($review['rating'])); ?></div>
                        </div>
                        <div class="comment-section"><q><?php echo htmlspecialchars($review['comment']); ?></q></div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No reviews available.</p>
            <?php endif; ?>
        </div>
    </div>
    <div class="submitreview-container">
        <div style="display: flex; flex-direction:column; align-items: center; justify-content: center; margin-left: 60px;">
            <div style="font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif; word-spacing: 3px; font-size: 40px;">Want to share your experience? </div>
            <div style="width: 500px; height: auto; margin-top: 30px;">Leave a review and get featured on our page instantly! Don't forget to give us your best comments!</div>
        </div>
        <div>
            <form action="" method="POST">
                <div class="leavereview-container">
                    <label for="rating">Rating:</label>
                    <select name="rating" id="rating" class="dropdown">
                        <option value="1" class="dropdown-option">1</option>
                        <option value="2" class="dropdown-option">2</option>
                        <option value="3" class="dropdown-option">3</option>
                        <option value="4" class="dropdown-option">4</option>
                        <option value="5" class="dropdown-option">5</option>
                    </select>
                    <label for="comment">Comment:</label>
                    <textarea name="comment" id="comment" cols="30" rows="5" style="border-radius: 10px; padding: 10px; border: 1px solid #ddd;"></textarea>
                    <input type="submit" value="Submit">
                </div>
            </form>
        </div>
    </div>
    <footer class="footer">
        <div>
            <div style="padding-left: 50px ;">CONNECT WITH US</div>
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
            <div style="margin-top: 5px;"><a href="about.html"
                    style="text-decoration: none;
            color: white">About us</a>
            </div>
            <div style="margin-top: 5px;"><a href="contact.html"
                    style="text-decoration: none;
            color: white">Contact Us</a>
            </div>
            <div style="margin-top: 5px;"><a href="termandcond.html"
                    style="text-decoration: none;
            color: white">Terms and Conditions</a>
            </div>
        </div>
        <div style="display: flex;
                justify-content: flex-end;
                width: 70%;">&copy; 2024 Photography Collective. All rights reserved
        </div>
    </footer>
</body>

</html>