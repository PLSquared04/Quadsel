<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

$email = "";
$msg = "";
function sendMail($email)
{
    $GLOBALS['otp'] = rand(10000, 99999);
    $_SESSION['otp'] = $GLOBALS['otp'];
    $mail = new PHPMailer(TRUE);
    try {   
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com;';
        $mail->SMTPAuth = true;

        // Mail ID and App password
        $mail->Username = 'praveenpandian04@gmail.com';
        $mail->Password = 'nmjyxutgkeojqwup';


        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('praveenpandian04@gmail.com', 'BlueBase');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Subject';
        $mail->Body = "HTML message body in <b>{$GLOBALS['otp']}</b> ";
        $mail->AltBody = 'Body in plain text for non-HTML mail clients';
        $mail->send();
        // echo "Mail has been sent successfully!";
    } catch (Exception $e) {
        header("Location: ../forgot-password?error=$mail->ErrorInfo");
    }
}


if ($_SERVER['REQUEST_METHOD'] != "POST") {
    header("Location: ../forgot-password");
} else {
    $email = $_POST["email"];
    
    // Database Connection
    include_once("../dbConnection.php");

    $sql = "SELECT * FROM employee_details WHERE email_id = '$email'";
    $result = $con->query($sql);
    $row = $result->fetch_assoc();
    $GLOBALS['emp_id'] = $row['emp_id'];
    if ($result->num_rows > 0) {
        sendMail($email);
        $_SESSION["change_password_emp_id"] = $row["emp_id"];
    } else {
        header("Location: ../forgot-password?error=Invalid email");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP</title>

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    
    <!-- Google Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="otp_style.css">
</head>
<body>
<div class="otp_form">
    <form name="form" action="../change_password/index.php" method="POST" onsubmit="return validateForm();">

        <h2>OTP Verification</h2>
        <p>Enter OTP sent to your registered email</p>
        <div class="input" id="inputs">
            <input type="text" inputmode="numeric" name="digit_1" maxlength="1" autocomplete="off">
            <input type="text" inputmode="numeric" name="digit_2" maxlength="1" autocomplete="off">
            <input type="text" inputmode="numeric" name="digit_3" maxlength="1" autocomplete="off">
            <input type="text" inputmode="numeric" name="digit_4" maxlength="1" autocomplete="off">
            <input type="text" inputmode="numeric" name="digit_5" maxlength="1" autocomplete="off">
        </div>
        <div id="error_msg"></div>
        <input type="text" name="emp_id" value="<?php echo $GLOBALS['emp_id'] ?>" style="display:none">
        <button class="btn" type="submit">Verify & Proceed</button>
    </form>

    <!-- Javascript code -->
    <script>
        const inputs = document.getElementById("inputs");
        let msg = document.getElementById("error_msg");

        inputs.addEventListener("input", function (e) {
            const target = e.target;
            const val = target.value;
            if (isNaN(val)) {
                console.log(1);
                target.value = "";
                return;
            }

            if (val != "") {
                console.log(2);
                const next = target.nextElementSibling;
                if (next) {
                    console.log(3);
                    next.focus();
                }
            }
        });

        inputs.addEventListener("keyup", function (e) {
            const target = e.target;
            const key = e.key.toLowerCase();

            if (key == "backspace" || key == "delete") {
                target.value = "";
                const prev = target.previousElementSibling;
                if (prev) {
                    prev.focus();
                }
                return;
            }
        });
        function validateForm() {
            let otp = <?php echo $otp ?>;
            let c_otp = document.form.digit_1.value + document.form.digit_2.value + document.form.digit_3.value + document.form.digit_4.value + document.form.digit_5.value;
            if(c_otp.length != 5){
                msg.style.display = "flex";
                msg.innerHTML = "Enter 5 digit OTP"
                return false;
            }
            if (otp != c_otp) {
                msg.style.display = "flex";
                msg.innerHTML = "Incorrect OTP"
                return false;
            }
            return true;
        } 
    </script>
</div>
</body>
</html>