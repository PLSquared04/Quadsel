<?php
function getCheckInOutData()
{
    include_once("../db_connection.php");
    $conn = OpenCon();
    $currentMonth = date('m');
    $currentYear = date('Y');
    $query = "SELECT date, SEC_TO_TIME(AVG(TIME_TO_SEC(check_out_time))) AS avg_check_out, SEC_TO_TIME(AVG(TIME_TO_SEC(check_in_time))) AS avg_check_in FROM `employee_attendance` WHERE YEAR(date) = 2023 AND MONTH(date) = 1";
    $check_in_out_stmt = $conn->prepare($query);
    // $check_in_out_stmt->bind_param("ii", $currentMonth, $currentYear);
    $check_in_out_stmt->execute();
    $check_in_out_stmt->bind_result($date, $avg_check_out_time, $avg_check_in_time);
    $analytics_data = array();
    while ($check_in_out_stmt->fetch()) {
        $analytics_data[$date] = array("avg_check_in" => $avg_check_in_time, "avg_check_out" => $avg_check_out_time);
    }
    $check_in_out_stmt->close();
    CloseCon($conn);
    return $analytics_data;

}

?>