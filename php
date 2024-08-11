<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = strip_tags(trim($_POST["name"]));
    $name = str_replace(array("\r","\n"), array(" "," "), $name);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $subject = trim($_POST["subject"]);
    $message = trim($_POST["message"]);

    // Prüfen, ob alle erforderlichen Felder ausgefüllt sind
    if (empty($name) OR empty($subject) OR empty($message) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Setzen Sie einen 400 (bad request) Response Code und exit.
        http_response_code(400);
        echo "Es ist ein Fehler aufgetreten. Bitte füllen Sie das Formular vollständig aus und versuchen Sie es erneut.";
        exit;
    }

    // E-Mail-Details
    $recipient = "info@alalo-transport.de"; // Ihre E-Mail-Adresse
    $subject = "Neue Nachricht von $name: $subject";

    // E-Mail-Inhalt
    $email_content = "Name: $name\n";
    $email_content .= "Email: $email\n";
    $email_content .= "Betreff: $subject\n";
    $email_content .= "Nachricht:\n$message\n";

    // E-Mail-Header
    $email_headers = "From: $name <$email>";

    // E-Mail senden
    if (mail($recipient, $subject, $email_content, $email_headers)) {
        // Setzen Sie einen 200 (okay) Response Code.
        http_response_code(200);
        echo "Vielen Dank! Ihre Nachricht wurde gesendet.";
    } else {
        // Setzen Sie einen 500 (internal server error) Response Code.
        http_response_code(500);
        echo "Hoppla! Etwas ist schiefgelaufen und wir konnten Ihre Nachricht nicht senden.";
    }
} else {
    // Nicht-POST-Anfragen sollten nicht erfolgreich sein
    http_response_code(403);
    echo "Es ist ein Problem aufgetreten. Bitte versuchen Sie es erneut.";
}
?>