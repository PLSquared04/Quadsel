<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $image = $_POST["image"];
    $emp_id = $_SESSION["emp_id"];

    // echo $image;
    // echo $emp_id;
    
    // Database Connection
    include_once("../dbCOnnection.php");

    $sql = "SELECT profile_photo_link FROM employee_details WHERE emp_id = '$emp_id';";
    $result = mysqli_query($con, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        $GLOBALS['profile'] = $row['profile_photo_link'];
    }

    // echo $profile;

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
        header("Location: ./index.php?error=$err");
    } else {
        $res = json_decode($response, true);
        if($res["hasError"]){
            $err = $res["statusMessage"];
            // echo "<script>window.location.href='./index.php?error='$err'</script>";
            header("Location: ./index.php?error=$err");
        }
        else if (strpos($res["data"]["resultMessage"], "two faces belong to the same person")) {
            $sql = "INSERT INTO employee_attendance (emp_id) VALUES ('$emp_id')";
            if (mysqli_query($con, $sql)) {
                // echo "<script>window.location.href='../'</script>";
                header("Location: ../");
            }
            else{
                echo "sadsa";
            }
        } 
        else {
            $err = $res["data"]["resultMessage"];
            // echo "<script>window.location.href='./index.php?error=$err</script>";
            header("Location: ./index.php?error=$err");
        }
    }
}
?>