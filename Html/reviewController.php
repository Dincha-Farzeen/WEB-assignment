<?php
class ReviewController {

    private $pdo;

    public function __construct() {
        try {
            $dsn = "mysql:host=localhost;dbname=photography_collective;charset=utf8mb4";
            $dbusername = "root";
            $dbpassword = "";
            $this->pdo = new PDO($dsn, $dbusername, $dbpassword);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }
    }

    
    public function getAllReviews() {
        try {
            $orderBy = "date ASC";
            if (isset($_GET['sortReview']) && $_GET['sortReview'] === 'newest') {
                $orderBy = "date DESC";
            }

            $sql = "SELECT r.rating, r.date, r.comment, u.u_name 
                    FROM reviews r 
                    JOIN registered_user u ON r.u_id = u.u_id 
                    ORDER BY $orderBy";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ['error' => 'Database error: ' . $e->getMessage()];
        }
    }

   
    public function addReview($data) {
        try {
            if (!isset($data['rating'], $data['comment'], $data['u_id'])) {
                return ['error' => 'Missing required fields.'];
            }

            $sql = "INSERT INTO reviews (rating, comment, u_id, date) VALUES (:rating, :comment, :u_id, NOW())";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':rating' => $data['rating'],
                ':comment' => $data['comment'],
                ':u_id' => $data['u_id'],
            ]);

            return ['success' => true, 'message' => 'Review added successfully.'];
        } catch (PDOException $e) {
            return ['error' => 'Failed to add review: ' . $e->getMessage()];
        }
    }
}
?>
