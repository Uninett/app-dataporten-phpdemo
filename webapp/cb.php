<?php
ini_set('display_errors', 1);

require_once 'vendor/autoload.php';

use fkooman\OAuth\Client\Guzzle3Client;
use fkooman\OAuth\Client\ClientConfig;
use fkooman\OAuth\Client\SessionStorage;
use fkooman\OAuth\Client\Api;
use fkooman\OAuth\Client\Context;
use fkooman\OAuth\Client\Callback;
use Guzzle\Http\Client;

$ENV_CLIENT = array(
	"authorize_endpoint" => "https://auth.dataporten.no/oauth/authorization",
	"client_id" 		 => getenv("DATAPORTEN_CLIENTID"),
	"client_secret"		 => getenv("DATAPORTEN_CLIENTSECRET"),
	"token_endpoint" 	 => "https://auth.dataporten.no/oauth/token",
	"redirect_uri" 		 => getenv("DATAPORTEN_REDIRECTURI"),
);

$ENV_SCOPES = explode(',', getenv("DATAPORTEN_SCOPES"));

$clientConfig = new ClientConfig($ENV_CLIENT);

if(isset($_GET['code'])) {
	try {
	    $tokenStorage = new SessionStorage();
	    $httpClient = new Guzzle3Client();

	    $cb = new Callback('foo', $clientConfig, $tokenStorage, $httpClient);
	    $cb->handleCallback($_GET);

	    header('HTTP/1.1 302 Found');
	    header('Location: ' . $ENV_CLIENT['redirect_uri']);
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
} else {

	$tokenStorage = new SessionStorage();
	$httpClient = new Guzzle3Client();
	$api = new Api('foo', $clientConfig, $tokenStorage, $httpClient);

	$context = new Context($ENV_CLIENT['client_id'], $ENV_SCOPES);

	$accessToken = $api->getAccessToken($context);
	if(isset($_GET['logout'])) {
		
		if(false === $accessToken) {
			setcookie("_ui", "", -1);

			header('HTTP/1.1 302 Found');
			header('Location: index.html');
			exit;
		}

		$tokenStorage->deleteAccessToken($accessToken);
		setcookie("_ui", "", -1);

		header('HTTP/1.1 302 Found');
		header('Location: index.html');
		exit;
	} else {
		
		if (false === $accessToken) {
		    header('HTTP/1.1 302 Found');
		    header('Location: '.$api->getAuthorizeUri($context));
		    exit;
		}

		$client 		 = new Client();
		$request 		 = $client->get('https://auth.dataporten.no/userinfo', array('Authorization' => "Bearer " . $accessToken->getAccessToken()));
		$response_client = $request->send();
		$response_client = json_decode($response_client->getBody(), true);

		
		$request 		 = $client->get('https://groups-api.dataporten.no/groups/me/groups', array('Authorization' => "Bearer " . $accessToken->getAccessToken()));
		$response_group  = $request->send();
		$response_group  = json_decode($response_group->getBody(), true);
		$groups 		 = array("groups" => $response_group);

		setcookie("_ui", base64_encode(json_encode(array_merge($response_client, $groups))));

		header('HTTP/1.1 302 Found');
		header('Location: index.html');
		exit;
	}
}