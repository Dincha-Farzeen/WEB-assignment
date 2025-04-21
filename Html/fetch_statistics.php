<?php
header('Content-Type: application/json');

try {
    $dsn = "mysql:host=localhost;dbname=photography_collective;charset=utf8mb4";
    $dbusername = "root";
    $dbpassword = "";

    $pdo = new PDO($dsn, $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $avgQuery = $pdo->query("SELECT AVG(rating) AS avg_rating FROM reviews");
    $avg_rating = $avgQuery ? number_format($avgQuery->fetch(PDO::FETCH_ASSOC)['avg_rating'], 1) : 0.0;

    $totalQuery = $pdo->query("SELECT COUNT(*) AS total_reviews FROM reviews");
    $total_reviews = $totalQuery->fetch(PDO::FETCH_ASSOC)['total_reviews'];

    $ratingQuery = $pdo->query("
        SELECT rating, COUNT(*) AS count, 
               (COUNT(*) * 100 / (SELECT COUNT(*) FROM reviews)) AS percentage 
        FROM reviews GROUP BY rating ORDER BY rating DESC
    ");
    $ratings = [];
    while ($row = $ratingQuery->fetch(PDO::FETCH_ASSOC)) {
        $ratings[$row['rating']] = [
            'count' => $row['count'],
            'percentage' => number_format($row['percentage'], 2) . '%'
        ];
    }

    echo json_encode([
        'avg_rating' => $avg_rating,
        'total_reviews' => $total_reviews,
        'ratings' => $ratings
    ]);
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['error' => 'Database error occurred.']);
}
?>
