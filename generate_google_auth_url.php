<?php
require_once 'vendor/autoload.php';

$clientID = '933379690745-04auqcdqbttk2sfplrhr4bq1ues1hkp6.apps.googleusercontent.com';
$clientSecret = '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';
$redirectUri = 'http://localhost/mindsoothe(1)/google_callback.php';

$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

echo $client->createAuthUrl();
?>
