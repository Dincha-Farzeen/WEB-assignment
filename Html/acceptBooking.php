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

            $conn->exec("SET innodb_lock_wait_timeout = 20");
            // get request details
            $sql = "SELECT user_id, photographer_id, startdate, enddate, description, location
            FROM requests r
            JOIN photographer p
            ON r.photographer_name = p.name
            WHERE request_id = $requestId"; 
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $temp = $stmt->fetch(PDO::FETCH_ASSOC);

            //delete request
            $sql = "DELETE FROM requests WHERE request_id = :request_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':request_id', $requestId, PDO::PARAM_INT);
            $stmt->execute();

            // insert booking
            $sql = "INSERT INTO booking (u_id, description, location)
                              VALUES (:uid, :description, :location)";
                
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':uid', $temp['user_id']);
                $stmt->bindParam(':description', $temp['description']);
                $stmt->bindParam(':location', $temp['location']);
                $stmt->execute();

            
            //get new bookingID
            $bookingID = $conn->lastInsertId();


            //update booking dates
            $start = new DateTime($temp['startdate']);
            $end = new DateTime($temp['enddate']);
            $end->modify('+1 day'); 

            $interval = new DateInterval('P1D');
            $daterange = new DatePeriod($start, $interval, $end);

            $stmt = $conn->prepare("INSERT INTO booking_dates (booking_id, booking_date) VALUES (:id, :date)");

            foreach ($daterange as $date) {
                
                $stmt->execute([
                    ':date' => $date->format('Y-m-d'),
                    ':id' => $bookingID
                ]);
            }
            
            // insert booked photographer
            $sql = "INSERT INTO booked_photographers (booking_id, photographer_id)
                              VALUES (:id,:pid)";
                
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':id', $bookingID);
                $stmt->bindParam(':pid', $temp['photographer_id']);
                 $stmt->execute();



            

            echo "success";
        

        $conn = null;

    } else {
        echo "error";
    }
}
?>
