<?php
    require '../vendor/autoload.php'; 
    use Opis\JsonSchema\Validator;
    use Opis\JsonSchema\Schema;

    header('Content-Type: application/json');

    try {
        
        $dsn = "mysql:host=localhost;dbname=photography_collective";
        $conn = new PDO($dsn, 'root', '');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        
        $sql = "SELECT bd.booking_date,
                    IFNULL(GROUP_CONCAT(DISTINCT p.name ORDER BY p.name SEPARATOR ', '),
                            'No photographers booked') AS photographers
                FROM booking b
                JOIN booking_dates bd ON b.booking_id = bd.booking_id
                LEFT JOIN booked_photographers bp ON b.booking_id = bp.booking_id
                LEFT JOIN photographer p ON bp.photographer_id = p.photographer_id
                GROUP BY bd.booking_date";

        $stmt = $conn->query($sql);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $data = json_decode(json_encode($results));  // converts from array of array to array of objects. works with api

        // Load schema
        $schemaData = file_get_contents('../Schemas/bookedDatesSchema.json');
        $schema = Schema::fromJsonString($schemaData);

        // Validate
        $validator = new Validator();
        $result = $validator->schemaValidation($data, $schema);

        if (!$result->isValid()) {
            echo json_encode([
                'success' => false,
                'message' => 'JSON schema validation failed',
                'errors'  => $result->getErrors()
            ]);
            exit;
        }

        // Return validated data
        echo json_encode($results);

    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'An error occurred',
            'error' => $e->getMessage()
        ]);
    }
?>
