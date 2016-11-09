<?php
ini_set('display_errors', 1);
session_start();
require_once 'vendor/autoload.php';

use Kasperrt\OAuth2;

$user_url = "https://auth.dataporten.no/userinfo";
$groups_url = "https://groups-api.dataporten.no/groups/me/groups";

$oauth2 = new OAuth2([
    "client_id"          => getenv('DATAPORTEN_CLIENTID'),
    "client_secret"      => getenv('DATAPORTEN_CLIENTSECRET'),
    "redirect_uri"       => getenv('DATAPORTEN_REDIRECTURI'),
    "auth"               => "https://auth.dataporten.no/oauth/authorization",
    "token"              => "https://auth.dataporten.no/oauth/token",
    "response_type" 	 => "code",
    "session"			 => true
]);

if(isset($_GET['code'])) {
	$access_token = $oauth2->get_access_token($_GET['state'], $_GET['code']);
	$user = $oauth2->get_identity($access_token, $user_url);
	$groups = $oauth2->get_identity($access_token, $groups_url);

	setcookie("_ui", base64_encode(json_encode(array_merge($user, array("groups" => $groups)))));

	header('HTTP/1.1 302 Found');
	header('Location: index.html');
	exit;
} else if(isset($_GET['logout'])) {
	setcookie("_ui", "", -1);

	session_destroy();

	header('HTTP/1.1 302 Found');
	header('Location: https://auth.dataporten.no/logout');
	exit;
}else {
	$oauth2->redirect();
	exit;
}