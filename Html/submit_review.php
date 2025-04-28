<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to leave a review.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rating'], $_POST['comment'])) {
    try {
        $dsn = "mysql:host=localhost;dbname=photography_collective;charset=utf8mb4";
        $dbusername = "root";
        $dbpassword = "";

        $pdo = new PDO($dsn, $dbusername, $dbpassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $rating = $_POST['rating'];
        $comment = $_POST['comment'];
        $userId = $_SESSION['user_id'];
        $date = date('Y-m-d H:i:s');

        $insertQuery = $pdo->prepare("INSERT INTO reviews (rating, date, comment, u_id) VALUES (?, ?, ?, ?)");
        $insertQuery->execute([$rating, $date, $comment, $userId]);

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Database error occurred.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
