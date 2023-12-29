<?php
function getAttendanceData()
{
    include_once("../db_connection.php");
    $conn = OpenCon();
    $currentMonth = date('m');
    $currentYear = date('Y');
    $query = "SELECT date, COUNT(*) FROM `employee_attendance` WHERE MONTH(date) = ? AND YEAR(date) = ? GROUP BY DATE";
    $emp_count_stmt = $conn->prepare($query);
    $emp_count_stmt->bind_param('ii', $currentMonth, $currentYear);
    $emp_count_stmt->execute();
    $emp_count_stmt->bind_result($date, $count);
    $analytics_data = array();
    while ($emp_count_stmt->fetch()) {
        $analytics_data[$date] = $count;
    }
    $emp_count_stmt->close();
    CloseCon($conn);
    return $analytics_data;
}


?>