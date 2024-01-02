<?php
session_start();
$phoneNumber = $_SESSION['phone_number'];
$verificationCode = $_POST['verification_code'];
$expectedCode = $_SESSION['expectedCode'];
$verification_time = $_SESSION['verification_time'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification du code de vérification par SMS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            text-align: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        p {
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
    // Vérifiez la validité du code pendant 1 minute (60 secondes)
    if (time() - $verification_time <= 60) {
        if ($verificationCode == $expectedCode) {
            echo '<p>Vérification réussie ! Code valide 3 minute.</p>';
            $_SESSION['expectedCode'] = $expectedCode;
            function getCurrentTime() {
                $currentTime = file_get_contents('http://worldtimeapi.org/api/timezone/Europe/Paris'); // Remplacez YOUR_TIMEZONE par le fuseau horaire approprié
                return json_decode($currentTime)->unixtime;
            }
            $_SESSION['code_timestamp'] = getCurrentTime();
            header("Refresh: 5; URL=index.php");
        } else {
            echo '<p>Code expiré. Veuillez générer un nouveau code.</p>';
            // Redirigez l'utilisateur vers la page de génération de code
            header("Refresh: 5; URL=askPin.php");
        }
    } else {
        echo '<p>Code de vérification incorrect. Veuillez réessayer.</p>';
        // Redirigez l'utilisateur vers la page de vérification
        header("Refresh: 5; URL=askPin.php");
    }
        ?>