<?php

    session_start();
    $user_name = $_SESSION['user_name'];

    $dsn = "mysql:host=localhost;dbname=photography_collective";
    $username = 'root';  
    $password = '';      

    try {
        $conn = new PDO($dsn, $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Database connection failed: " . $e->getMessage();
    }

    $sql = "SELECT b.booking_id, b.description, b.location, b.price, b.booking_id, u.u_name,
                GROUP_CONCAT(DISTINCT bd.booking_date ORDER BY bd.booking_date SEPARATOR ', ') AS booking_dates,
                GROUP_CONCAT(DISTINCT p.name ORDER BY p.name SEPARATOR ', ') AS photographers,
                MIN(bd.booking_date) AS first_booking_date
        FROM booking b
        JOIN booking_dates bd ON b.booking_id = bd.booking_id
        LEFT JOIN booked_photographers bp ON b.booking_id = bp.booking_id
        LEFT JOIN photographer p ON bp.photographer_id = p.photographer_id
        LEFT JOIN registered_user u ON b.u_id = u.u_id 
        GROUP BY b.booking_id
        ORDER BY first_booking_date ASC"; 

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sql = "SELECT COUNT(*) AS count
            FROM requests";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $requestCount = $stmt->fetch(PDO::FETCH_ASSOC);

    $sql = "SELECT COUNT(DISTINCT(b.booking_id)) AS count
            FROM booking b
            JOIN booking_dates bd ON b.booking_id = bd.booking_id
            WHERE booking_date >= CURDATE() ";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $totalBookingCount = $stmt->fetch(PDO::FETCH_ASSOC);

    $sql = "SELECT COUNT(DISTINCT(b.booking_id)) AS count
            FROM booking b
            JOIN booking_dates bd ON b.booking_id = bd.booking_id
            WHERE MONTH(bd.booking_date) = MONTH(CURDATE()) 
            AND YEAR(bd.booking_date) = YEAR(CURDATE())";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $bookingCount = $stmt->fetch(PDO::FETCH_ASSOC);


    $conn = null;
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Bookings</title>
        <link rel="stylesheet" href="../css/admin.css">
        <style>
            .main-content {
                margin-left: 200px;
                flex: 1;
                padding: 20px;
            }

            .header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 15px;
            }

            .stats {
                display: flex;
                gap: 20px;
                margin-top: 20px;
            }

            .stat-box {
                flex: 1;
                background: white;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
                text-align: center;
            }

            .stat-box h3 {
                font-size: 18px;
                margin-bottom: 10px;
                color: #555;
            }

            .stat-box p {
                font-size: 24px;
                font-weight: bold;
            }

            .tables {
                display: flex;
                margin-top: 20px;
                gap: 20px;
            }

            .table-box {
                flex: 1;
                background: white;
                padding: 35px;
                border-radius: 10px;
                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 10px;
            }

            table, th, td {
                border: 0px ;
                text-align: left;
                padding: 8px;
            }

            th {
                background: #f1f1f1;
            }

            .btn{
                width: 75px;
                padding: 10px;
                font-family: "Lucida Sans", "Lucida Sans Regular", "Lucida Grande",
                    "Lucida Sans Unicode", Geneva, Verdana, sans-serif;
                background-color:  rgba(63, 48, 29, 0.62);
                color: white;
                border: none;
                border-radius: 20px;
                cursor: pointer;
                font-size: 80%;
                position: relative;
            }

            .btn:hover {
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            }
        
            .modal {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                display: flex;
                justify-content: center;
                align-items: center;
                backdrop-filter: blur(10px);
            }

            .modal-content {
                background-color: white;
                padding: 20px;
                border-radius: 8px;
                text-align: left;
                position: relative;
                width: 80%;
                max-width: 500px;
                max-height: 80%;
                overflow-y: auto;
            }

            .modal-content h2 {
                text-align: center;
                margin-bottom:20px;
            }

            .close {
                position: absolute;
                top: 10px;
                right: 15px;
                font-size: 20px;
                font-weight: bold;
                cursor: pointer;
            }

            .btn-save{
                width: 75px;
                padding: 10px;
                font-family: "Lucida Sans", "Lucida Sans Regular", "Lucida Grande",
                    "Lucida Sans Unicode", Geneva, Verdana, sans-serif;
                background-color:  rgba(63, 48, 29, 0.62);
                color: white;
                border: none;
                border-radius: 20px;
                cursor: pointer;
                font-size: 80%;
                position: relative;
            }

            .btn-save:hover {
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            }

            input[type="text"],
            textarea {
                background-color: rgb(219, 216, 216);
                width: 60%;
                padding: 10px;
                border: transparent;
                border-radius: 20px;
                transition: border 0.3s ease;
                animation: fadeIn 0.5s ease forwards;
                transition: all 0.3s ease;
                animation-delay: 0.5s;
                position: relative;
                margin: 5px;
                font-family: "Lucida Sans", "Lucida Sans Regular", "Lucida Grande",
                    "Lucida Sans Unicode", Geneva, Verdana, sans-serif;
                    font-size:12px;
            }

            input[type="text"]:focus,
            textarea:focus {
                border: 1px solid rgb(116, 116, 116);
                outline: none;
            }

            input[type="text"]:hover,
            textarea:hover{
            box-shadow: 0 4px 3px rgba(0, 0, 0, 0.1);
            }
        </style>
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>
    <body>
        <div class="sidebar">
            <h2>Admin Tools</h2>
            <div class="menu">
                <a href="#" class="active"><i class="fas fa-calendar-alt"></i> Bookings</a>
                <a href="requestsDashboard.php"><i class="fas fa-envelope"></i> Requests</a>
                <a href="homepage.html"><i class="fa-solid fa-house"></i> Home</a>
            </div>
        </div>
        <div class="main-content">
            <div class="header">
                <h2>Welcome to Dashboard, <?php echo htmlspecialchars($user_name); ?>.</h2>
            </div>
            <div class="stats">
                <div class="stat-box">
                    <h3>Total upcoming bookings</h3>
                    <p><?php echo htmlspecialchars($totalBookingCount['count']); ?></p>
                </div>
                <div class="stat-box">
                    <h3>Bookings for this month</h3>
                    <p><?php echo htmlspecialchars($bookingCount['count']); ?></p>
                </div>
                <div class="stat-box">
                    <h3>Requests</h3>
                    <p><?php echo htmlspecialchars($requestCount['count']); ?></p>
                </div>
            </div>
            <div class="tables">
                <div class="table-box">
                    <h4>Browse bookings...</h4>
                    <div style="display:flex; align-items:center;margin-top:30px; flex-direction:row;justify-content: space-between;">
                        <button class="btn" id="prevMonth">Previous</button>
                        <h3 style="color:#3f301d;" id="currentMonth"></h3>
                        <button class="btn" id="nextMonth">Next</button>
                    </div>
                    <table id="bookingTable">
                        <tr>
                            <th>Date</th>
                            <th>Customer Name</th>
                            <th>Location</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Photographers</th>
                            <th></th>
                        </tr>
                        <?php foreach ($bookings as $booking): 
                            $first_date = strtotime(explode(", ", $booking['booking_dates'])[0]); 
                            $month = date("F", $first_date);
                            $year = date("Y", $first_date);  
                        ?>
                        <tr data-booking-id="<?= htmlspecialchars($booking['booking_id']) ?>"
                            data-month-year="<?php echo $month . '-' . $year; ?>"> 
                            
                            <td>
                                <?php 
                                    $booking_dates = explode(", ", $booking['booking_dates']);
                                    $formatted_dates = array_map(fn($date) => date("d", strtotime($date)), $booking_dates); 
                                    echo implode(", ", $formatted_dates); 
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($booking['u_name']); ?></td>
                            <td class="col-location"><?php echo htmlspecialchars($booking['location']); ?></td>
                            <td class="col-description"><?php echo htmlspecialchars($booking['description']); ?></td>
                            <td class="col-price"><?php echo htmlspecialchars($booking['price']); ?></td>
                            <td><?php echo htmlspecialchars($booking['photographers']); ?></td>
                            <td> <button class="btn" style="padding:6px;">Edit</button></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
        <div id="edit-modal" class="modal" style="display: none;">
            <div class="modal-content">
                <span id="close-modal-btn" class="close">&times;</span>
                <h2>Edit Booking</h2>

                <input type="hidden" id="edit-booking-id">

                <div style="justify-content:space-between; display:flex;margin-bottom:10px; ">
                    <label>Location</label>
                    <input type="text" id="edit-location">
                </div>

                <div style="justify-content:space-between; display:flex;margin-bottom:10px;">
                <label>Description</label>
                <textarea id="edit-description"></textarea>
                </div>

                <div style="justify-content:space-between; display:flex;margin-bottom:10px;">
                <label>Price</label>
                <input type="text" id="edit-price">
                </div>

                <button id="btn-save" class="btn-save">Save</button>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                let currentDate = new Date();
                let currentMonthIndex = currentDate.getMonth();
                let currentYear = currentDate.getFullYear();

                let months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

                function updateTable() {
                    let selectedMonthYear = months[currentMonthIndex] + '-' + currentYear; 
                    $("#bookingTable tr").each(function() {
                        if ($(this).data("month-year") === selectedMonthYear || $(this).find("th").length > 0) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                    $("#currentMonth").text(months[currentMonthIndex] + " " + currentYear); 
                }

                updateTable(); 

                $("#nextMonth").click(function() {
                    if (currentMonthIndex < 11) {
                        currentMonthIndex++;
                    } else {
                        currentMonthIndex = 0;
                        currentYear++; 
                    }
                    updateTable();
                });

                $("#prevMonth").click(function() {
                    if (currentMonthIndex > 0) {
                        currentMonthIndex--;
                    } else {
                        currentMonthIndex = 11;
                        currentYear--; 
                    }
                    updateTable();
                 });
            });

            $('#bookingTable').on('click', '.btn', function(){
                var $row = $(this).closest('tr');

                // get booking-id
                var id  = $row.data('booking-id');

                // get detais
                var loc  = $row.find('.col-location').text().trim();
                var desc = $row.find('.col-description').text().trim();
                var price= $row.find('.col-price').text().trim();

                // fill form fields
                $('#edit-booking-id').val(id);
                $('#edit-location').val(loc);
                $('#edit-description').val(desc);
                $('#edit-price').val(price);

                // show the modal
                $('#edit-modal').fadeIn();
            });

           
            $('#close-modal-btn').on('click', function(){
                $('#edit-modal').fadeOut();
            });

            $('#btn-save').on('click', function(){
                const payload = {
                    bookingid:   parseInt($('#edit-booking-id').val(), 10), // Parse as integer to match json schema
                    location:    $('#edit-location').val(),
                    description: $('#edit-description').val(),
                    price:       parseFloat($('#edit-price').val()) // Parse as float
                };

            $.ajax({
                url: 'editBooking.php',    
                type: 'POST',
                contentType: 'application/json',
                dataType: 'json',           // expect JSON back
                data: JSON.stringify(payload),
                success: function(resp){
                    if (resp.success) {

                        // Update the row 
                        var $row = $('#bookingTable').find('tr[data-booking-id="'+payload.bookingid+'"]');
                        $row.find('.col-location').text(payload.location);
                        $row.find('.col-description').text(payload.description);
                        $row.find('.col-price').text(payload.price);

                    // hide the modal
                    $('#edit-modal').fadeOut();
                    } else {
                    alert('Update failed: ' + resp.message);
                    }
                },
                error: function(){
                    alert('An unexpected error occurred.');
                }
                });
            });


        </script>
    </body>
</html>
