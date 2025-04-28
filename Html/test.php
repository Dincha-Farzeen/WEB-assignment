<?php
header('Content-Type: application/json');
if (!isset($_POST['bookingid'])) {
  echo json_encode(['success'=>false,'message'=>'No ID provided']);
  exit;
}

$id   = $_POST['bookingid'];
$loc  = $_POST['location'];
$desc = $_POST['description'];
$price= $_POST['price'];

if (empty($id) || empty($loc) || empty($desc) || empty($price) ) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit();
}

$desc = filter_var(trim($desc), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$loc = filter_var(trim($loc), FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$dsn = "mysql:host=localhost;dbname=photography_collective";
$username = 'root';
$password = '';

try {
  $conn = new PDO($dsn, $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo "Database connection failed: " . $e->getMessage();
}

try {
    $sql = "UPDATE booking 
            SET location = :loc, description = :desc, price = :price 
            WHERE booking_id = :id";
  $stmt = $conn->prepare("$sql");
  $stmt->execute([
    ':loc'   => $loc,
    ':desc'  => $desc,
    ':price' => $price,
    ':id'    => $id
  ]);
  echo json_encode(['success'=>true]);

  $conn = null;

} catch (Exception $e) {
  echo json_encode(['success'=>false,'message'=>$e->getMessage()]);
}
