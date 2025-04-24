<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in to leave a review.']);
    exit();
}

$data = $_POST;

$rating = filter_var($data['rating'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 5]]);
if ($rating === false) {
    echo json_encode(['success' => false, 'message' => 'Rating must be an integer between 1 and 5.']);
    exit();
}

$comment = trim($data['comment']);
if (empty($comment) || strlen($comment) > 1000) {
    echo json_encode(['success' => false, 'message' => 'Comment must be a non-empty string with a maximum length of 1000 characters.']);
    exit();
}

try {
    $dsn = "mysql:host=localhost;dbname=photography_collective;charset=utf8mb4";
    $dbusername = "root";
    $dbpassword = "";

    $pdo = new PDO($dsn, $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $userId = $_SESSION['user_id'];
    $date = date('Y-m-d H:i:s');

    $insertQuery = $pdo->prepare("INSERT INTO reviews (rating, date, comment, u_id) VALUES (?, ?, ?, ?)");
    $insertQuery->execute([$rating, $date, $comment, $userId]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error occurred.']);
}
?>