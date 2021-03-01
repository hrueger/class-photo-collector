# Class Photo Collector

## Setup
Reister an App like described here: https://docs.microsoft.com/de-DE/graph/tutorials/php?tutorial-step=2


Create a file called `secret.php` and fill it with the following lines:
```PHP
<?php 
$_ENV["OAUTH_APP_ID"] = "YOUR_APP_ID_HERE";
$_ENV["OAUTH_APP_SECRET"] = "YOUR_APP_SECRET_HERE";
$_ENV["OAUTH_REDIRECT_URI"] = "THE_URL_TO_THE_callback.php_FILE";
$_ENV["OAUTH_SCOPES"] = "openid profile offline_access user.read";
$_ENV["OAUTH_AUTHORITY"] = "https://login.microsoftonline.com/YOUR_TENANT_ID";
$_ENV["OAUTH_AUTHORIZE_ENDPOINT"] = "/oauth2/v2.0/authorize";
$_ENV["OAUTH_TOKEN_ENDPOINT"] = "/oauth2/v2.0/token";
?>
```