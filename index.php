<?php
session_start();
$expectedCode = $_SESSION['expectedCode'];
$code_timestamp = $_SESSION['code_timestamp'];

// Vérifie également la validité du code de vérification
$isValidVerificationCode = isValidVerificationCode($expectedCode, $code_timestamp);

if ($isValidVerificationCode) {
    // Rediriger vers la page d'opération si le code est encore valide
    echo "Votre code est encore valide";
    exit();
} else {
        // Redirection vers la page d'askPin.php si le temps écoulé est supérieur à 3 minute
        header('Location: askPin.php');
        exit();
}

function isValidVerificationCode($expectedCode, $code_timestamp)
{
    $expiration_time = 180; // 3 minutes en secondes
    return !empty($expectedCode) && time() - $code_timestamp <= $expiration_time;
}
