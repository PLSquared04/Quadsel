<?php
function verifyadmin($pwd)
{
    include_once '.../../../../../db_connection.php';
    $admin_email = 'admin@email.com';
    $conn = OpenCon();
    $query = "SELECT * FROM `login` WHERE email=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $admin_email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($result_email, $result_password);
    $status = false;
    while ($stmt->fetch()) {
        if (password_verify($pwd, $result_password)) {
            $status = true;
        }
    }
    $stmt->close();
    CloseCon($conn);
    return $status;
}
?>