<?php
    $dsn = "mysql:host=localhost;dbname=photography_collective";
    $username = 'root';  
    $password = '';      

    try {
        $conn = new PDO($dsn, $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Database connection failed: " . $e->getMessage();
    }       

    $sql = "SELECT description,photographer_name,u_name,startdate,enddate,location,u_email,request_id
            FROM requests r
            JOIN registered_user u ON r.user_id = u.u_id"; 

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $conn = null;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requests</title>
    <link rel="stylesheet" href="../css/admin.css">
    <style>
        .main-content {
            margin-left: 200px;
            flex: 1;
            padding: 20px;
            width:70%;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
        }

        .calendar-container {  
            gap: 8px;
            width: 22%;
            background-color:  #f8f9fc;
            color: white;
            position: right;
            height: 100%;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            padding: 10px;
            
        }

        .calendar {
            background: white;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%; 
            text-align: center;
            height:470px;
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1px;
            margin-top: 1px;
            color: black;
            font-size: 15px; 
        }

        .calendar-header button {
            background: none;
            border: none;
            font-size: 15px;
            cursor: pointer;
            color: #555;
        }

        .calendar table {
            width: 100%;
            border-collapse: collapse;
        }

        .calendar th, .calendar td {
            padding: 8px;
            text-align: center;
            color: #555;
            font-size: 15px;
        }

        .calendar th {
            font-weight: bold;
            color: #888;
             
        }

        .calendar .active {
            background:rgba(63, 48, 29, 0.62);
            color: white;
            border-radius: 50%;
            padding: 10px 10px;
        }

        .booking-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .booking-table, .booking-table th, .booking-table td {
            border: 1px solid #ddd;
            text-align: center;
            padding: 8px;   
            font-size: 15px;  
        }

        .booking-table th {
            background: #f1f1f1;
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

        

        .task-list {
            background:rgba(63, 48, 29, 0.62);
            padding: 30px;
            border-radius: 10px;
            color: white;
            width: 100%;  
            text-align: center;
            height: 500px;
            
        }

        .task-list h1 {
            font-size: 50px;
            margin-bottom: 5px;
        }

        .task-list p {
            font-size: 15px;
            opacity: 0.9;
        }

        .task-list h3 {
            justify-self: start;
            font-size: 16px;
            margin-bottom: 15px;
            margin-top: 15px;
        }

        .task-list ol {
            margin-top: 20px;
            text-align: left;
        }

        .task-list li {
            list-style: none;
            margin-bottom: 10px;
            font-size: 15px;
            list-style: circle;
        }
        
        button {
            width: 75px;
            color: white;
            height: 30px;
            border-radius: 30px;
            text-decoration: none;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: rgba(63, 48, 29, 0.62);
            font-family: Cambria, Cochin, Georgia, Times, "Times New Roman", serif;
        }

        #table th, #table td {
            border: none; 
            background-color: white;  
            font-size: 15px;  
        }

        #table{
            border:none;
            margin-left:10px;
            margin-top: 40px;
        }

    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="sidebar">
        <h2>Admin Tools</h2>
        <div class="menu">
            <a href="bookingsDashboard.php"><i class="fas fa-calendar-alt"></i> Bookings</a>
            <a href="requestsDashboard.php" class="active"><i class="fas fa-envelope"></i> Requests</a>
            <a href="#"><i class="fas fa-users"></i> List of Customers</a>
        </div>
    </div>

    <div class="main-content">
        <div class="header">
            <h2>Welcome to Dashboard</h2>
        </div>

        <div class="tables">
            <div class="table-box">
                <h4>Incoming Requests...</h4>
                <div style="display: flex; flex-direction: row;">
                <table id="bookingTable" class="booking-table">
                    <tr>
                        <th>From</th>
                        <th>To</th>
                        <th>Customer Name</th>
                        <th>Location</th>
                        <th>Description</th>
                        <th>Photographer(s)</th>
                    </tr>
                    <?php foreach ($requests as $request): 
                        $startdate = new DateTime($request['startdate']);
                        $enddate = new DateTime($request['enddate']);
                    
                        $formattedStartdate = $startdate->format('d/m/y');
                        $formattedEnddate = $enddate->format('d/m/y');?>
                        <tr>
                            <td><?php echo htmlspecialchars($formattedStartdate); ?></td>
                            <td><?php echo htmlspecialchars($formattedEnddate); ?></td>
                            <td><?php echo htmlspecialchars($request['u_name']); ?></td>
                            <td><?php echo htmlspecialchars($request['location']); ?></td>
                            <td><?php echo htmlspecialchars($request['description']); ?></td>
                            <td><?php echo htmlspecialchars($request['photographer_name']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <table id="table" class="booking-table" style="width:20%;">
                    <tr>
                        <th></th>
                        <th></th>
                    </tr>
                    <?php foreach ($requests as $request): ?>
                        <tr id="row-<?php echo $request['request_id']; ?>">
                            <td><button 
                                class="accept-btn"
                                data-id="<?php echo $request['request_id']; ?>"
                                data-email="<?php echo htmlspecialchars($request['u_email']); ?>"
                                data-name="<?php echo htmlspecialchars($request['u_name']); ?>"
                                data-description="<?php echo htmlspecialchars($request['description']); ?>">
                                Accept
                            </button></td>
                            <td><button 
                                class="decline-btn"
                                data-id="<?php echo $request['request_id']; ?>"
                                data-email="<?php echo htmlspecialchars($request['u_email']); ?>"
                                data-name="<?php echo htmlspecialchars($request['u_name']); ?>"
                                data-description="<?php echo htmlspecialchars($request['description']); ?>">
                                Decline
                            </button></td>        
                       </tr>
                    <?php endforeach; ?>
                </table>
                </div>
            </div>
        </div>
    </div>
 
        <!-- Calendar Section with Task List on the Right -->
        <div class="calendar-container">
            <!-- Calendar -->
            <div class="calendar">
                <div class="calendar-header">
                    <button onclick="prevMonth()"><i class="fa-solid fa-angle-left"></i></button>
                    <h2 id="month-year"></h2>
                    <button onclick="nextMonth()"><i class="fa-solid fa-angle-right"></i></button>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Mo</th> <th>Tu</th> <th>We</th> <th>Th</th> <th>Fr</th> <th>Sa</th> <th>Su</th>
                        </tr>
                    </thead>
                    <tbody id="calendar-body"></tbody>
                </table>
            </div>

            <!-- Task List Section (Pink Part) -->
            <div class="task-list">
                <h1 id="selected-date"></h1>
                <p id="selected-day"></p>
                <h3>Booked Photographers</h3>
                <ol id="photographers-list">
                    
                    
                </ol>
            </div>
        </div>

    <script>
        let currentDate = new Date();
        let bookedPhotographers = [];  
        const monthYear = document.getElementById("month-year");
        const calendarBody = document.getElementById("calendar-body");
        const selectedDate = document.getElementById("selected-date");
        const selectedDay = document.getElementById("selected-day");

        function renderCalendar() {
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();
            const firstDay = new Date(year, month, 1).getDay();
            const lastDate = new Date(year, month + 1, 0).getDate();
            
            // Adjust for Monday start
            let startDay = (firstDay === 0) ? 6 : firstDay - 1;

            monthYear.innerText = new Intl.DateTimeFormat('en', { month: 'long', year: 'numeric' }).format(currentDate);
            calendarBody.innerHTML = "";

            let row = document.createElement("tr");
            let cellCount = 0;

            // Empty cells for the beginning of the month
            for (let i = 0; i < startDay; i++) {
                row.appendChild(document.createElement("td"));
                cellCount++;
            }

            // Fill dates
            for (let date = 1; date <= lastDate; date++) {
                let cell = document.createElement("td");
                cell.innerText = date;
                cell.addEventListener("click", function () {
                    selectedDate.innerText = date;
                    selectedDay.innerText = new Intl.DateTimeFormat('en', { weekday: 'long' }).format(new Date(year, month, date));
                    highlightSelected(cell);

                    // Format the clicked date 
                    let selectedFullDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(date).padStart(2, '0')}`;


                    // Find the photographers for the selected date
                    let photographers = bookedPhotographers
                        .filter(entry => entry.booking_date === selectedFullDate)
                        .map(entry => entry.photographers) // Get the photographer names
                        .join(", ");

                    // Update the task list
                    let taskList = document.querySelector(".task-list ol");
                    taskList.innerHTML = photographers ? `<li>${photographers.replace(/, /g, "</li><li>")}</li>` : "<li>No photographers booked</li>";
                });
                if (date === currentDate.getDate() && month === new Date().getMonth() && year === new Date().getFullYear()) {
                    cell.classList.add("active");
                }

                row.appendChild(cell);
                cellCount++;

                if (cellCount === 7) {
                    calendarBody.appendChild(row);
                    row = document.createElement("tr");
                    cellCount = 0;
                }
            }

            if (cellCount > 0) {
                for (let i = cellCount; i < 7; i++) {
                    row.appendChild(document.createElement("td"));
                }
                calendarBody.appendChild(row);
            }
        }

        function highlightSelected(cell) {
            document.querySelectorAll("#calendar-body td").forEach(td => td.classList.remove("active"));
            cell.classList.add("active");
        }

        function prevMonth() {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar();
        }

        function nextMonth() {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar();
        }
     

        renderCalendar();



        $(document).ready(function(){
       
            $.getJSON('getBookedDates.php')
            .done(function(data) {
                bookedPhotographers = data;
                
                
            renderCalendar();

            // auto‑click the “today” cell so the list shows immediately
            const todayCell = document.querySelector("#calendar-body td.active");
            if (todayCell) todayCell.click();
            })
            .fail(function(err){
                console.error("Could not load booked dates:", err);
            });


            $('.accept-btn').click(function(){
            const requestId = $(this).data('id');
            const email = $(this).data('email');
            const name = $(this).data('name');
            const description = $(this).data('description');
            const row = $('#row-' + requestId);

            $.ajax({
                url: 'acceptBooking.php',
                type: 'POST',
                data: {
                    request_id: requestId,
                },
                success: function(response){
                    if (response === 'success') {
                        const subject = encodeURIComponent(description + ' booking');
                        const body = encodeURIComponent(`Hello ${name},

                        This is to inform you that the booking requested for has been accepted. We would be truly honored to capture your special day(s).
                        We'll set up a meeting soon to finalise the details of the shoot and prepare a quotation.

                        Kind regards,

                        Photography Collective.`);

                        window.location.href = `mailto:${email}?subject=${subject}&body=${body}`;

                        // Hide both buttons
                        row.find('.accept-btn, .decline-btn').hide();

                        //upfate calendar
                        $.getJSON('getBookedDates.php', function(data) {
                            bookedPhotographers = data;      
                            renderCalendar();
                        });

                    } else {
                        alert('Something went wrong. Please try again.');
                    }
                },
                error: function(){
                    alert('Server error. Please try again.');
                }
            });
            });

            $('.decline-btn').click(function(){
                const requestId = $(this).data('id');
                const email = $(this).data('email');
                const name = $(this).data('name');
                const description = $(this).data('description');
                const row = $('#row-' + requestId);
                $.ajax({
                    url: 'rejectBooking.php',
                    type: 'POST',
                    data: {
                        request_id: requestId,
                    },
                    success: function(response){
                        if (response === 'success') {
                            const subject = encodeURIComponent(description + ' booking');
                            const body = encodeURIComponent(`Hello ${name},

                            We regret to inform you that we will be unable to proceed with your booking due to the photographer being unavailable for that time.
                            However, you can put in a request for another photographer and we'll be sure to give it a special consideration.
                            We hope to we'll have an opportunity to collaborate again in the future. 
                            Kind regards,

                            Photography Collective.`);

                            window.location.href = `mailto:${email}?subject=${subject}&body=${body}`;

                            // Hide both buttons
                            row.find('.accept-btn, .decline-btn').hide();

                    } else {
                        alert('Something went wrong. Please try again.');
                    }
                },
                error: function(){
                    alert('Server error. Please try again.');
                }
            });

    });
});
    </script>
</body>
</html>
