<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $image = $_POST["image"];

    $emp_id = $_SESSION["emp_id"];

    // Database Connection
    include_once("../dbConnection.php");
    
    $sql = "SELECT profile_photo_link FROM employee_details WHERE emp_id = '$emp_id';";
    $result = mysqli_query($con, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        $GLOBALS['profile'] = $row['profile_photo_link'];
    }

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://face-verification2.p.rapidapi.com/faceverification",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "image1Base64=$image&image2Base64=$profile",
        CURLOPT_HTTPHEADER => [
            "X-RapidAPI-Host: face-verification2.p.rapidapi.com",
            "X-RapidAPI-Key: 437d8536fdmsh517633e7c412eb8p144a84jsn35b3e056ac89",
            "content-type: application/x-www-form-urlencoded"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "<script>window.location.href='./index.php?error=$err'</script>";
    } else {
        $res = json_decode($response, true)["data"]["resultMessage"];
        if (strpos($res,"two faces belong to the same person.")) {
            date_default_timezone_set('Asia/Kolkata');
            $date = date('Y-m-d');
            $time = date("H:i:s", time());
            $sql = "UPDATE employee_attendance SET check_out_time = '$time' WHERE emp_id = '$emp_id' and date = '$date'";

            if (mysqli_query($con, $sql)) {
                echo "<script>window.location.href='../'</script>";
            }
        } else {
            echo "<script>window.location.href='./index.php?error=$res'</script>";
        }
    }
}
else{
    header("Location: ../");
}

?>