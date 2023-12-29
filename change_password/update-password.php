<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emp_id = $_POST["emp_id"];
    echo $_POST["password"];
    $emp_password = password_hash($_POST["password"],PASSWORD_DEFAULT);
    echo $emp_password;

    // Database Connection
    include_once("../dbConnection.php");

    $sql = "UPDATE login SET password = '$emp_password' WHERE emp_id = '$emp_id'";
    $con->query($sql);
    // header("Location: ../login");
}
?>