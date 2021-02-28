<?php

require_once("./vendor/autoload.php");
require_once("./secret.php");
session_start();
function redirect($u) {
    header("Location: " . $u);
}

function getClient() {
    return new \League\OAuth2\Client\Provider\GenericProvider([
        'clientId'                => "800c280a-18fb-491f-ba51-b04c4e407e55",
        'clientSecret'            => "~-.3Xkt9pXuFXArr86~~j._AHzxKLsJl4~",
        'redirectUri'             => "http://localhost/class-photo-collector/callback.php",
        'urlAuthorize'            => "https://login.microsoftonline.com/479c6849-8e4e-4438-8f9b-e6d1e9f9f7ec/oauth2/v2.0/authorize",
        'urlAccessToken'          => "https://login.microsoftonline.com/479c6849-8e4e-4438-8f9b-e6d1e9f9f7ec/oauth2/v2.0/token",
        'urlResourceOwnerDetails' => '',
        'scopes'                  => "openid profile offline_access user.read mailboxsettings.read calendars.readwrite"
      ]);
}
?>