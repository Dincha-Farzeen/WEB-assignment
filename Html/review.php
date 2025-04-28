<?php
session_start(); 

try {
    $dsn = "mysql:host=localhost;dbname=photography_collective;charset=utf8mb4";
    $dbusername = "root";
    $dbpassword = "";

    $pdo = new PDO($dsn, $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $avg_rating = 0.0; 
    $ratings = []; 

    $totalQuery = $pdo->query("SELECT COUNT(*) AS total_reviews FROM reviews");
    $total_reviews = $totalQuery->fetch(PDO::FETCH_ASSOC)['total_reviews'];

    $avgQuery = $pdo->query("SELECT AVG(rating) AS avg_rating FROM reviews");
    if ($avgQuery) {
        $avg_rating = number_format($avgQuery->fetch(PDO::FETCH_ASSOC)['avg_rating'], 1);
    }

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            
            $('#reviewForm').on('submit', function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'check_session.php', //endpoint to check session status
                    method: 'POST',
                    dataType: 'json',
                    success: function (response) {
                        if (response.loggedIn) {

                            //submit the review if logged in
                            fetch('/assignment/Html/webservices/restHandler.php?request=review', {   //mini api to get reviews in json
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'   //content sent is json
                            },
                            body: JSON.stringify({                          //converts data into a JSON string
                                    u_id: $('#reviewForm [name=u_id]').val(),
                                    rating: $('#reviewForm [name=rating]').val(),
                                    comment: $('#reviewForm [name=comment]').val()
                                })

                            })
                            .then((res) => {
                            if (!res.ok) {
                               
                                throw new Error(`Server returned ${res.status}`);
                            }
                            return res.json();          // parse JSON response
                            })
                            .then((response) => {
                            if (response.success) {
                                $('#successPopup').fadeIn();  //display attribute was none and display message
                                loadReviews();
                                loadStatistics();
                                document.getElementById('reviewForm').reset();
                            } else {
                                alert(response.message || 'An error occurred.');
                            }
                            })
                            .catch((err) => {
                            console.error('Fetch error:', err);
                            alert('An error occurred while submitting the review.');
                            });
                        } else {
                            // Show popup if not logged in
                            $('#loginPopup').fadeIn();
                        }
                    },
                    error: function () {
                        alert('An error occurred while checking login status.');
                    }
                });
            });

            //handle sorting
            $('#sortForm').on('submit', function (e) {
                e.preventDefault();
                loadReviews(); //reload reviews dynamically with the selected sort order
            });

            //load reviews dynamically
            function loadReviews() {
                $.ajax({
                    url: '/assignment/Html/webservices/restHandler.php?request=review', //endpoint to fetch reviews
                    method: 'GET',
                    data: $('#sortForm').serialize(), //Turn them into a URL-encoded string and send in the request.
                    success: function (response) {
                        console.log("returning: ", response);
                        $('#reviewsContainer').empty();

                        $.each(response, function(_, review) {
                            const $review = $('<div>').addClass('reviews');

                            const $info   = $('<div>').css({
                                display: 'flex',
                                flexDirection: 'column',
                                gap: '7px'
                            });
                            $info.append($('<div>').html('<b>Name:</b> ').append(review.u_name));
                            $info.append($('<div>').html('<b>Date:</b> ').append(review.date));

                            // stars
                            const $stars = $('<div>').html('<b>Review:</b> ');
                            for (let i = 0; i < review.rating; i++) {
                            $stars.append('<span class="material-icons">star</span>');
                            }
                            $info.append($stars);

                            $review.append($info);
                            $review.append($('<div>').addClass('comment-section').html('"'+review.comment+'"'));
                            
                            $('#reviewsContainer').append($review);
                        });
                    },
                    error: function () {
                        alert('An error occurred while loading reviews.');
                    }
                });
            }

            // Function to load statistics dynamically
            function loadStatistics() {
                $.ajax({
                    url: 'fetch_statistics.php', // Endpoint to fetch statistics
                    method: 'GET',
                    success: function (response) {
                        $('#averageRating').text(response.avg_rating);
                        $('#totalReviews').text(response.total_reviews);
                        for (let i = 5; i >= 1; i--) {
                            $(`#star${i}Percentage`).text(response.ratings[i]?.percentage || '0.00%');
                            $(`#star${i}Count`).text(response.ratings[i]?.count || 0);
                        }
                    },
                    error: function () {
                        alert('An error occurred while loading statistics.');
                    }
                });
            }

            // Close popup on cancel button click
            $('#cancelButton').on('click', function () {
                $('#loginPopup').fadeOut();
            });

            // Redirect to login page on login button click
            $('#loginButton').on('click', function () {
                window.location.href = 'login.php';
            });

            // Close success popup on OK button click
            $('#successOkButton').on('click', function () {
                $('#successPopup').fadeOut();
            });

            // Initial load of reviews and statistics
            loadReviews();
            loadStatistics();
        });
    </script>
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

    /* Popup styling */
    #loginPopup, #successPopup {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 300px;
        background-color: white;
        border: 1px solid #ddd;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        padding: 20px;
        z-index: 1000;
        text-align: center;
    }

    #loginPopup button, #successPopup button {
        margin: 10px;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    #cancelButton {
        background-color: #f44336;
        color: white;
    }

    #loginButton, #successOkButton {
        background-color: #4CAF50;
        color: white;
    }

    #popupOverlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 999;
    }
</style>

<body>
    <div id="popupOverlay"></div>
    <div id="loginPopup">
        <p>You need to login before leaving a review.</p>
        <button id="cancelButton">Cancel</button>
        <button id="loginButton">Login</button>
    </div>
    <div id="successPopup" style="display: none;">
        <p>Your review has been submitted successfully!</p>
        <button id="successOkButton">OK</button>
    </div>
    <nav>
        <a class="homeactive" href="homepage.html">Home</a>
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
            <p style="font-weight: 700; font-size: 20px; margin-left: 10px">Reviews</p>
            <div>
                <div style="text-align: center; margin-top: 20px">
                    <p style="font-size: 60px; margin-top: -10px; font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif; display: inline;" id="averageRating">
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
                        <b style="font-size: 20px; margin-left: 13px" id="star<?php echo $i; ?>Percentage">
                            <?php echo $ratings[$i]['percentage'] ?? '0.00%'; ?>
                        </b>
                        (<span id="star<?php echo $i; ?>Count"><?php echo $ratings[$i]['count'] ?? 0; ?></span> reviews)
                    </p>
                </div>
            <?php endfor; ?>
        </div>

    </div>
    <div class="review-container">
        <form id="sortForm" action="" method="POST">
            <div class="filter-container">
                <div style="font-size: 20px;">FILTER REVIEWS</div>
                <select name="sortReview" class="dropdown">
                    <option value="oldest" class="dropdown-option">Oldest First</option>
                    <option value="newest" class="dropdown-option">Newest First</option>
                </select>
                <input type="submit" value="Sort">
            </div>
        </form>
        <div id="reviewsContainer" class="display-review">
            <!-- Reviews will be dynamically loaded here -->
        </div>
    </div>
    <div class="submitreview-container">
        <div style="display: flex; flex-direction:column; align-items: center; justify-content: center; margin-left: 60px;">
            <div style="font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif; word-spacing: 3px; font-size: 40px;">Want to share your experience? </div>
            <div style="width: 500px; height: auto; margin-top: 30px;">Leave a review and get featured on our page instantly! Don't forget to give us your best comments!</div>
        </div>
        <div>
            <form id="reviewForm" action="" method="POST">
                <div class="leavereview-container">
                    <input type="hidden" name="u_id" value="<?php echo $_SESSION['user_id']; ?>">
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

