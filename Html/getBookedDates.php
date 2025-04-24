<?php
    header('Content-Type: application/json');
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
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
?>
