<?php
require_once("./lib.php");
// $expectedState = $_SESSION['oauthState'];
// unset($_SESSION['oauthState']);
$providedState = $_REQUEST['state'];

/* if (!isset($expectedState)) {
    // If there is no expected state in the session,
    // do nothing and redirect to the home page.
    return redirect('/');
}

if (!isset($providedState) || $expectedState != $providedState) {
   ?>
    error: Invalid auth state<br>
    errorDetail: The provided auth state did not match the expected value'
   <?
   exit();
}
*/
// Authorization code should be in the "code" query param
$authCode = $_REQUEST['code'];
if (isset($authCode)) {
    // Initialize the OAuth client
    $oauthClient = new \League\OAuth2\Client\Provider\GenericProvider([
        'clientId'                => $_ENV['OAUTH_APP_ID'],
        'clientSecret'            => $_ENV['OAUTH_APP_SECRET'],
        'redirectUri'             => $_ENV['OAUTH_REDIRECT_URI'],
        'urlAuthorize'            => $_ENV['OAUTH_AUTHORITY'].$_ENV['OAUTH_AUTHORIZE_ENDPOINT'],
        'urlAccessToken'          => $_ENV['OAUTH_AUTHORITY'].$_ENV['OAUTH_TOKEN_ENDPOINT'],
        'urlResourceOwnerDetails' => '',
        'scopes'                  => $_ENV['OAUTH_SCOPES']
      ]);
    try {
        // Make the token request
        $accessToken = $oauthClient->getAccessToken('authorization_code', [
            'code' => $authCode
        ]);

        $graph = new Graph();
        $graph->setAccessToken($accessToken->getToken());

        $user = $graph->createRequest('GET', '/me?$select=displayName,mail,mailboxSettings,userPrincipalName')
            ->setReturnType(Model\User::class)
            ->execute();

        var_dump($user);

        $tokenCache = new TokenCache();
        $tokenCache->storeTokens($accessToken, $user);

        // return redirect('/');
    }
    catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
   ?>
   error: Error requesting access token<br>
   errorDetail: <?php print json_encode($e->getResponseBody()) ?>
  <?
    exit();
}
}

    ?>
    error: Error <?php print $_REQUEST['error'] ?><br>
    errorDetail: <?php print $_REQUEST['error_description'] ?>
   <?
    exit();
