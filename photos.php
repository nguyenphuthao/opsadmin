<?php
session_start();
require_once __DIR__ . '/Facebook/autoload.php';
$fb = new Facebook\Facebook([
  'app_id' => '1274810639224071',
  'app_secret' => 'f6bbee3a8a8e9c0ae56f97893bf66af8',
  'default_graph_version' => 'v2.8',
  //'default_access_token' => '{access-token}', // optional
]);
$accessToken = $_SESSION['fb_access_token'];

$request = $fb->get('/me/photos?fields=tags,full_picture', $accessToken);


// $request = new Facebook\FacebookRequest(
//   $accessToken,
//   'GET',
//   '/me/photos'
// );

// $response = $request->execute();
// $graphObject = $response->getGraphObject();
/* handle the result */



// echo "<pre>";
// print_r($graphObject);
// echo "</pre>";
// exit();


// $fbApp = new Facebook\FacebookApp('1274810639224071', 'f6bbee3a8a8e9c0ae56f97893bf66af8');
// $request = new Facebook\FacebookRequest($fbApp, 'accessToken', 'GET', '/me/photos');
// $response = $request->execute();
// $graphObject = $response->getGraphObject();
/* handle the result */
$photos = $request->getBody();
$photos = json_decode($photos);
echo "<pre>";
print_r($photos);
echo "</pre>";
// Send the request to Graph


// Sets the default fallback access token so we don't have to pass it to each request
$fb->setDefaultAccessToken($accessToken);

try {
  $response = $fb->get('/me/');
  $userNode = $response->getGraphUser();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

echo 'Logged in as ' . $userNode->getName();

