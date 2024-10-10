<?php
require_once 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$clientId = getenv('CLIENT_ID');
$clientSecret = getenv('CLIENT_SECRET');

$redirectUri = 'http://localhost/mindsoothe(1)/google_callback.php';

$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

echo $client->createAuthUrl();
?>
