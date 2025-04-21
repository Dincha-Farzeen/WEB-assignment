<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $dsn = "mysql:host=localhost;dbname=photography_collective;charset=utf8mb4";
        $dbusername = "root";
        $dbpassword = "";

        $pdo = new PDO($dsn, $dbusername, $dbpassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $orderBy = "date ASC";
        $selectedFilter = $_POST['sortReview'] ?? 'oldest';
        if ($selectedFilter === 'newest') {
            $orderBy = "date DESC";
        }

        $sql = "SELECT r.rating, r.date, r.comment, u.u_name 
                FROM reviews r 
                JOIN registered_user u ON r.u_id = u.u_id 
                ORDER BY $orderBy";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($reviews as $review) {
            echo '<div class="reviews">';
            echo '<div style="display: flex; flex-direction: column; gap: 7px">';
            echo '<div><b>Name:</b> ' . htmlspecialchars($review['u_name']) . '</div>';
            echo '<div><b>Date:</b> ' . htmlspecialchars($review['date']) . '</div>';
            echo '<div><b>Review:</b>' . str_repeat('<span class="material-icons">star</span>', htmlspecialchars($review['rating'])) . '</div>';
            echo '</div>';
            echo '<div class="comment-section"><q>' . htmlspecialchars($review['comment']) . '</q></div>';
            echo '</div>';
        }
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        echo '<p style="color: red; font-weight: bold;">An error occurred while fetching reviews.</p>';
    }
}
?>
