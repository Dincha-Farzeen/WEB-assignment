<?php
session_start();

require_once  'reviewController.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $controller = new ReviewController();
        $reviews = $controller->getAllReviews();  // Fetch all reviews

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
