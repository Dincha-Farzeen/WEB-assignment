<?php
require '../vendor/autoload.php';
use Opis\JsonSchema\Validator;
use Opis\JsonSchema\Schema;

header('Content-Type: application/json');

// Load and decode the schema
$schemaData = file_get_contents('../Schemas/editBookingSchema.json');
$schema = Schema::fromJsonString($schemaData);

// Decode the raw POST data
$raw = file_get_contents('php://input');
$data = json_decode($raw);

// Validate JSON structure
$validator = new Validator();
$result = $validator->schemaValidation($data, $schema);

if (!$result->isValid()) {
    $errors = $result->getErrors();
    echo json_encode([
        'success' => false,
        'message' => 'JSON validation failed',
        'errors'  => $result->getErrors()
    ]);
    exit;
}

// Extract and sanitize
$payload = json_decode($raw, true); // For easier PHP access
$id     = (int) $payload['bookingid'];
$loc    = filter_var(trim($payload['location']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$desc   = filter_var(trim($payload['description']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$price  = number_format((float)$payload['price'], 2, '.', '');

// DB setup
$dsn = "mysql:host=localhost;dbname=photography_collective";
$username = 'root';
$password = '';

try {
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "UPDATE booking 
            SET location = :loc, description = :desc, price = :price 
            WHERE booking_id = :id";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':loc'   => $loc,
        ':desc'  => $desc,
        ':price' => $price,
        ':id'    => $id
    ]);

    echo json_encode(['success' => true]);
    $conn = null;

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
