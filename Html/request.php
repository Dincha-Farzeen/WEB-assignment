<?php
session_start();

$host = "localhost";
$dbname = "Photography_collective";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'User not logged in']);
        exit();
    }

    $user_id = $_SESSION['user_id'];

    $photographer = trim($_POST['photographer_name'] ?? '');
    $startdate = $_POST['startdate'] ?? '';
    $enddate = $_POST['enddate'] ?? '';
    $description = filter_var(trim($_POST['descr'] ?? ''), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $location = filter_var(trim($_POST['location'] ?? ''), FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (empty($photographer) || empty($startdate) || empty($enddate) || empty($description) || empty($location)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        exit();
    }

    $sql = "INSERT INTO requests (user_id, photographer_name, startdate, enddate, description, location) 
            VALUES (:user_id, :photographer, :startdate, :enddate, :description, :location)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':user_id' => $user_id,
        ':photographer' => $photographer,
        ':startdate' => $startdate,
        ':enddate' => $enddate,
        ':description' => $description,
        ':location' => $location,
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Booking request submitted successfully',
        'data' => [
            'photographer' => $photographer,
            'startdate' => $startdate,
            'enddate' => $enddate,
            'description' => $description,
            'location' => $location
        ]
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>