<?php

require_once 'vendor/autoload.php';

use fkooman\OAuth\Client\Guzzle3Client;
use fkooman\OAuth\Client\ClientConfig;
use fkooman\OAuth\Client\SessionStorage;
use fkooman\OAuth\Client\Callback;

$clientConfig = new ClientConfig(
    array(
        'authorize_endpoint' => 'https://auth.dataporten.no/oauth/authorization',
        'client_id' => '75741f37-85fa-41a1-ae54-7f6f39b39f61',
        'client_secret' => '2dfefed8-7445-46c3-a39a-c1f11acf39b9',
        'token_endpoint' => 'https://auth.dataporten.no/oauth/token',
    )
);

try {
    $tokenStorage = new SessionStorage();
    $httpClient = new Guzzle3Client();

    $cb = new Callback('foo', $clientConfig, $tokenStorage, $httpClient);
    $cb->handleCallback($_GET);

    //header('HTTP/1.1 302 Found');
    //header('Location: http://localhost/fkooman/php-oauth-client/example/simple/index.php');
    exit;
} catch (fkooman\OAuth\Client\Exception\AuthorizeException $e) {
    // this exception is thrown by Callback when the OAuth server returns a
    // specific error message for the client, e.g.: the user did not authorize
    // the request
    die(sprintf('ERROR: %s, DESCRIPTION: %s', $e->getMessage(), $e->getDescription()));
} catch (Exception $e) {
    // other error, these should never occur in the normal flow
    die(sprintf('ERROR: %s', $e->getMessage()));
}