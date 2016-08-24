<?php

use TheIconic\Tracking\GoogleAnalytics\Analytics;

require __DIR__ . '/../vendor/autoload.php';

ini_set('display_errors', 0);
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);

// Settings (edit)
$tracking_id = 'UA-20202020-1';

$ip = $_SERVER['REMOTE_ADDR'];
$document_path = $_SERVER['REQUEST_URI'];
$client_id = md5($ip);

$url = NULL;

if (isset($_GET['url'])) {
  $url = filter_var($_GET['url'], FILTER_SANITIZE_URL);
}

$url = filter_var($url, FILTER_VALIDATE_URL) ? $url : NULL;

$lang = explode(';', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
$lang = explode(',', $lang[0]);

if (!$url) {
  header('HTTP/1.1 400 Bad Request');
  echo '400 Bad Request';
  exit;
}

$ga = new Analytics();
$ga
  ->setProtocolVersion('1')
  ->setTrackingId($tracking_id)
  ->setClientId($client_id)
  ->setDocumentPath($document_path)
  ->setIpOverride($ip)
  ->setUserLanguage($lang);
$ga->sendPageview();

header('HTTP/1.1 301 Moved Permanently');
header("Location: $url");
exit;
