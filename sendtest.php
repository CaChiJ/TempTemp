<?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    require __DIR__ . '/vendor/autoload.php';
    use Twilio\Rest\Client;

    // Your Account SID and Auth Token from twilio.com/console
    $account_sid = 'AC640e3f42a5cf174bd0261fc009716d64';
    $auth_token = '6644b5cd2a7c0b3f2abbe03e387010d1';
    // In production, these should be environment variables. E.g.:
    // $auth_token = $_ENV["TWILIO_AUTH_TOKEN"]

    // A Twilio number you own with SMS capabilities
    $twilio_number = "+18125671382";

    $client = new Client($account_sid, $auth_token);
    $client->messages->create(
        // Where to send a text message (your cell phone?)
        '+8201028427239',
        array(
            'from' => $twilio_number,
            'body' => 'I sent this message in under 10 minutes!'
        )
    );
?>