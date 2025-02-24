<?php
try {
    // Database connection details
    $dsn = "mysql:host=localhost;dbname=photography_collective;charset=utf8mb4";
    $dbusername = "root";
    $dbpassword = "";

    // Create a new PDO instance
    $pdo = new PDO($dsn, $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare and execute the SQL statement
    $sql = "SELECT u_name FROM registered_user ORDER BY u_name ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Log the error and show a generic message
    error_log("Database error: " . $e->getMessage());
    $error = "database_error";
}
?>
