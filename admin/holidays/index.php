<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_FILES["holiday_data"])) {
            $file_name = "holiday.xlsx";
            $file_tmp = $_FILES["holiday_data"]["tmp_name"];
            $file_size = $_FILES["holiday_data"]["size"];
            $file_type = $_FILES["holiday_data"]["type"];
            $upload_dir = "./uploads/";
            $upload_path = $upload_dir . $file_name;
            if (move_uploaded_file($file_tmp, $upload_path)) {

                require_once '../../vendor/autoload.php';
                include_once('../../dbConnection.php');

                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                $filename = './uploads/holiday.xlsx';
                $spreadsheet = $reader->load($filename);

                $worksheet = $spreadsheet->getActiveSheet();
                $rows = $worksheet->toArray();

                unset($rows[0]);
                foreach ($rows as $row) {
                    echo $row[0] . " " . $row[1];
                    $date = date("Y-m-d", strtotime(date($row[0])));
                    $sql = "INSERT INTO company_holidays VALUES('$date','$row[1]')";
                    $result = mysqli_query($con, $sql);
                }
            } else {
                echo "Error uploading file.";
            }
        } else {
            echo "File input not found.";
        }
    }
    ?>

</body>

</html>