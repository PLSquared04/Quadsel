<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $GLOBALS['emp_id'] = $_POST['emp_id'];
} else {
    header("Location: ../forgot-password");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>

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
        <form name="form" action="./update-password.php" method="POST" onsubmit="return validateForm();">
            <div class="alert alert-danger" id="msg"></div>
            <h2>Change Password</h2>
            <div class="input-group password">
                <span class="material-symbols-outlined input-group-text">
                    lock
                </span>
                <input class="form-control" type="password" name="password" placeholder="Password">
            </div>
            <div class="input-group password">
                <span class="material-symbols-outlined input-group-text">
                    lock
                </span>
                <input class="form-control" type="text" name="c_password" placeholder="Confirm Password">
            </div>
            <input type="text" name="emp_id" value="<?php echo $emp_id ?>" hidden>
            <button class="btn">CHANGE PASSWORD</button>
        </form>
    </div>

    <!-- Javascript code -->
    <script>
        function validateForm(){
            let pass = document.form.password.value;
            let c_pass =document.form.c_password.value;
            let msg =document.getElementById("msg");
            msg.style.display = "flex";
            if(pass.trim() == "")
                msg.innerHTML = "Enter the Password";
            else if(c_pass.trim() == "")
                msg.innerHTML = "Enter the Confirm Password";
            else if(pass != c_pass)
                msg.innerHTML = "Password does not match";
            else
                return true;

            return false;

        }
    </script>
</body>
</html>