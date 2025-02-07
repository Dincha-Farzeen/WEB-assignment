<?php
// Database connection
$dsn = "mysql:host=localhost;dbname=photography_collective";
$username = 'root';
$password = '';

try {
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
    exit;
}

// Fetch booked dates
$bookedDates = [];
$sql = "SELECT booking_date FROM booking_dates";
$stmt = $conn->prepare($sql);
$stmt->execute();
$bookedDates = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

// Get current month and year from URL parameters, default to today
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('m');
$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

// Adjust month and year for navigation
if ($month < 1) {
    $month = 12;
    $year--;
} elseif ($month > 12) {
    $month = 1;
    $year++;
}

// Days in the selected month
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

// Start of the selected month
$firstDayOfMonth = date('N', strtotime("$year-$month-01"));

// Generate the calendar
function generateCalendar($year, $month, $daysInMonth, $firstDayOfMonth, $bookedDates)
{
    $calendar = '<table border="1" cellpadding="5" cellspacing="0" style="text-align:center;">';
    $calendar .= '<tr>
                    <th>Sun</th><th>Mon</th><th>Tue</th>
                    <th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th>
                  </tr>';

    $calendar .= '<tr>';

    // Empty cells for days before the first of the month
    for ($i = 1; $i < $firstDayOfMonth; $i++) {
        $calendar .= '<td></td>';
    }

    // Fill in the days of the month
    for ($day = 1; $day <= $daysInMonth; $day++) {
        $date = sprintf('%04d-%02d-%02d', $year, $month, $day);
        if (in_array($date, $bookedDates)) {
            $calendar .= "<td style='background-color: gray; color: white;'>$day</td>";
        } else {
            $calendar .= "<td>$day</td>";
        }

        // Start a new row every Sunday
        if (($day + $firstDayOfMonth - 1) % 7 == 0) {
            $calendar .= '</tr><tr>';
        }
    }

    // Empty cells for days after the last of the month
    while (($day + $firstDayOfMonth - 1) % 7 != 0) {
        $calendar .= '<td></td>';
        $day++;
    }

    $calendar .= '</tr>';
    $calendar .= '</table>';

    return $calendar;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Calendar</title>
</head>
<body>
    <h1>Booking Calendar</h1>
    
    <!-- Navigation Controls -->
    <div>
        <a href="?month=<?= $month - 1; ?>&year=<?= $year; ?>">Previous</a>
        <span><?= date('F Y', strtotime("$year-$month-01")); ?></span>
        <a href="?month=<?= $month + 1; ?>&year=<?= $year; ?>">Next</a>
    </div>

    <!-- Calendar -->
    <?php
    echo generateCalendar($year, $month, $daysInMonth, $firstDayOfMonth, $bookedDates);
    ?>

    <!-- Dropdown for Direct Month/Year Selection -->
    <form method="GET" action="">
        <label for="month">Month:</label>
        <select name="month" id="month">
            <?php
            for ($m = 1; $m <= 12; $m++) {
                $selected = ($m == $month) ? 'selected' : '';
                echo "<option value='$m' $selected>" . date('F', mktime(0, 0, 0, $m, 1)) . "</option>";
            }
            ?>
        </select>

        <label for="year">Year:</label>
        <input type="number" name="year" id="year" value="<?= $year; ?>" min="1900" max="2100">

        <button type="submit">Go</button>
    </form>
</body>
</html>
