<?php
$error = "";
parse_str($_SERVER['QUERY_STRING'], $res);
$GLOBALS['error'] = isset($res['error']) ? $res['error'] : "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check In</title>

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
    
    <!-- Webcam CDN -->
    <script type="text/javascript" src="https://unpkg.com/webcam-easy/dist/webcam-easy.min.js"></script>
</head>
<body>
    <!-- Check In verification -->
    <div class="check_in_verify">
        <form action="./check_in.php" method="POST" onsubmit="return handleImage();" class="check_in_container">
        <?php
        if (trim($error) != "")
            echo "<div class='alert alert-danger'>$error</div>";
        ?>
            <video id="webcam" autoplay></video>
            <canvas id="canvas"></canvas>
            <button class="btn btn-light" type="submit">CHECK IN</button>

            <!-- Base64 Image -->
            <input id="image" type="text" name="image" style="display:none" />
        </form>
    </div>

    <!-- Javascript Code -->

    <script>
    const webcamElement = document.getElementById('webcam');
        const canvasElement = document.getElementById('canvas');
        const webcam = new Webcam(webcamElement, 'user',canvasElement);
        webcam.start()
                .then(result =>{
                    console.log("webcam started");
                })
                .catch(err => {
                    console.log(err);
                });

        function handleImage(){
            document.getElementById("image").value = webcam.snap();
            return true;
        }

    </script>

</body>
</html>