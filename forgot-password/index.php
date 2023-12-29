<?php
$msg = "";
parse_str($_SERVER['QUERY_STRING'], $arr);
if(isset($arr['error']))
    $msg = $arr['error']

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    
    <!-- Google Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form">
        <form name="form" action="../verify_otp/index.php" method="POST">
            <h2>Forgot Password</h2>
            <p>Enter your email and we'll send an OTP to verify the account</p>
            <div class="input-group">
                <span class="material-symbols-outlined input-group-text">
                    mail
                </span>
                <input type="email" class="form-control" name="email" placeholder="Email">
            </div>
            <?php 
                if(trim($msg) != "")
                    echo "
                        <label class='error-msg'>$msg</label>
                    "
            ?>
            <button class="btn w-100 btn" type="submit">GET OTP</button>
        </form>
    </div>
    </body>
    </html>