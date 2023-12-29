<?php
session_start();

if(!isset($_SESSION["emp_id"])){
    header("Location: ../login");
}

// Database details
include_once("../dbCOnnection.php");

class details
{
    public $date;
    public $check_in;
    public $check_out;
}


$mon_arr = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
$day_arr = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

$month = isset($_REQUEST['month']) ? $_REQUEST['month'] : date("m");
$year = isset($_REQUEST['year']) ? $_REQUEST['year'] : date("Y");

$date = strtotime("$year-$month-1");

// First day of the month
$start_date = date("w", $date);
// No of days in the month
$end_date = date("t", $date);

//user_id
$emp_id = $_SESSION["emp_id"];

// Today date
$today_date = date("d");

$sql = "SELECT emp_name FROM employee_details WHERE emp_id = '$emp_id'";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_array($result);
$emp_name = $row["emp_name"];


// Update Attendace till Yesterday
$sql = "UPDATE employee_attendance SET check_out_time = '20:00' WHERE date < '$year-$month-$today_date' and check_out_time = '00:00:00'";
mysqli_query($con, $sql);

$sql = "SELECT * FROM employee_attendance WHERE date >= '$year-$month-01' and date <= '$year-$month-31' and emp_id = '$emp_id'";
$result = mysqli_query($con, $sql);

//Monthly employee_details data
$data = array();

while ($row = mysqli_fetch_array($result)) {
    $user_det = new details();
    $user_det->date = strtotime($row["date"]);
    $user_det->check_in = $row["check_in_time"];
    $user_det->check_out = $row["check_out_time"];
    $data[(int) date("d", $user_det->date)] = $user_det;
}

include_once("generateCalendar.php");

// Generate Holiday dates
include_once("generateHolidayDates.php");
$holiday = generateHolidayDates($year,$month);

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

    <!-- Google Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <nav>
        <img src="../assests/image.jpeg" alt="no-image">
        
         <a href="../profile" class="profile">
            <h6 id="name">Hi <?php echo $emp_name ?>,</h6>
        </a>

    </nav>
    
    <div class="calendar">
        <div class="header">
            <a href="<?php echo './?month=' . ($month - 1 < 1 ? 12 : $month - 1) . '&year=' . ($month == 1 ? $year - 1 : $year) ?>">
                <span class="material-symbols-outlined">
                    arrow_back_ios
                </span>
            </a>
            <?php
            echo "<h4 class='month'>{$mon_arr[$month - 1]} - {$year}</h4>";
            ?>
            <a href="<?php echo './?month=' . ($month + 1 > 12 ? 1 : $month + 1) . '&year=' . ($month == 12 ? $year + 1 : $year) ?>">
            <span class="material-symbols-outlined">
                arrow_forward_ios
            </span>
            </a>
        </div>

        <div class='cal_header'>
            <?php
            for ($i = 0; $i < 7; $i++) {
                echo "<h5 class='day'>$day_arr[$i]</h5>";
            }
            ?>
        </div>

        <div class="dates">
            <?php
            $extra = [];
            if ($start_date == 5 and $end_date == 31) {
                $extra = [31, 0, 0, 0, 0, 0, 0];
                $end_date = 30;
            } else if ($start_date == 6 and $end_date == 31) {
                $extra = [30, 31, 0, 0, 0, 0, 0];
                $end_date = 29;
            } else if ($start_date == 6 and $end_date == 30) {
                $extra = [30, 0, 0, 0, 0, 0, 0];
                $end_date = 29;
            } else {
                $extra = [0, 0, 0, 0, 0, 0, 0];
            }

            for ($i = 0; $i < $start_date; $i++) {
                    generateCalendarDate($year,$month,$extra[$i],$data,$holiday);
            }


            for ($i = 1; $i <= $end_date; $i++) {
                generateCalendarDate($year,$month,$i,$data,$holiday);
            }

            for($i=($start_date+$end_date)+1;$i<36;$i++) 
                generateCalendarDate($year,$month,0,$data,$holiday);
            ?>
        </div>
    </div> 
</body>
</html>