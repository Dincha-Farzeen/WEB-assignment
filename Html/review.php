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

$sql = "SELECT r.date, r.rating, r.comment, u.user_name 
        FROM reviews r JOIN registered_user u 
        WHERE (r.u_id = u.u_id)";

$stmt = $conn->prepare($sql);
$stmt->execute();
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);   //$reviews is an array
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Reviews</title>
    <style>
        body {
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            padding: 20px;
            background-color: grey;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: black;
        }
        .review {
            border-bottom: 1px solid #greycc;
            padding: 15px 0;
        }
        
        .date {
            color: white;
            font-size: 14px;
        }
        .rating {
            font-weight: bold;
            color: black;
        }
        .comment {
            margin: 10px 0;
            color: white;
        }
        .user {
            font-size: 16px;
            color: #white;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Client Reviews</h1>

    <?php if (count($reviews) > 0): ?>
        <?php foreach ($reviews as $review): ?>
            <div class="review">
                <div class="date"><?php echo htmlspecialchars($review['date']); ?></div>
                <div class="rating">Rating: <?php echo htmlspecialchars($review['rating']); ?>/5</div>
                <div class="comment"><?php echo htmlspecialchars($review['comment']); ?></div>
                <div class="user">- Reviewed by <?php echo htmlspecialchars($review['user_name']); ?></div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No reviews found.</p>
    <?php endif; ?>
</div>

</body>
</html>

<?php
$conn = null;
?>
