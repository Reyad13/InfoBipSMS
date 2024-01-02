<?php
use Infobip\Configuration;
use Infobip\Api\SmsApi;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsTextualMessage;
use Infobip\Model\SmsAdvancedTextualRequest;

require __DIR__ . "/vendor/autoload.php";
session_start();

function getCurrentTime() {
    $currentTime = file_get_contents('http://worldtimeapi.org/api/timezone/Europe/Paris'); // Remplacez YOUR_TIMEZONE par le fuseau horaire approprié
    return json_decode($currentTime)->unixtime;
}
    $phoneNumber = '+33' . $_POST['phone_number'];
    $expectedCode = rand(1000, 9999);

    $message = 'Votre code de vérification est : ' . $expectedCode;
    $SENDER = "Mysaamp";

    $BASE_URL = "https://y31p2j.api.infobip.com";
    $API_KEY = "6d4e9a4e2a83924fc09f35f369a58af6-8ba7c116-e8c2-477a-8983-178c66a1de0f";

    $configuration = new Configuration(host: $BASE_URL, apiKey: $API_KEY);
    $sendSmsApi = new SmsApi(config: $configuration);

    $destination = new SmsDestination(
        to: $phoneNumber
    );

    $message = new SmsTextualMessage(destinations: [$destination], from: $SENDER, text: $message);

    $request = new SmsAdvancedTextualRequest(messages: [$message]);

    try {
        $smsResponse = $sendSmsApi->sendSmsMessage($request);

        echo $smsResponse->getBulkId() . PHP_EOL;

        foreach ($smsResponse->getMessages() ?? [] as $message) {
            echo sprintf('Message ID: %s, status: %s', $message->getMessageId(), $message->getStatus()?->getName()) . PHP_EOL;
        }
    } catch (Throwable $apiException) {
        echo("HTTP Code: " . $apiException->getCode() . "\n");
    }

    $_SESSION['expectedCode'] = $expectedCode;
    $_SESSION['phone_number'] = $phoneNumber;
    $_SESSION['verification_time'] = getCurrentTime();

    echo '<p>Un code de vérification a été envoyé à votre numéro de téléphone.</p>';
    echo '<p>Veuillez saisir le code ci-dessous :</p>';
    echo '<form method="post" action="verify.php">';
    echo '<input type="text" name="verification_code" placeholder="Code de vérification" required>';
    echo '<button type="submit">Valider</button>';
    echo '</form>';
?>
