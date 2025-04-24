<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestId = $_POST['request_id'] ?? null;

    if ($requestId) {
        $dsn = "mysql:host=localhost;dbname=photography_collective";
        $username = 'root';  
        $password = '';      

        try {
            $conn = new PDO($dsn, $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Database connection failed: " . $e->getMessage();
        }
            //delete request
            $sql = "DELETE FROM requests WHERE request_id = :request_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':request_id', $requestId, PDO::PARAM_INT);
            $stmt->execute();
            echo "success";

        $conn = null;

    } else {
        echo "error";
    }
}
?>