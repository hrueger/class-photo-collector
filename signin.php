<?php
    require_once("./lib.php");

    $authUrl = "https://login.microsoftonline.com/".$_ENV["OAUTH_TENANT_ID"]."/oauth2/v2.0/authorize";
    $authUrl .= "?client_id=".$_ENV["OAUTH_APP_ID"];
    $authUrl .= "&response_type=code";
    $authUrl .= "&redirect_uri=".$_ENV["OAUTH_REDIRECT_URI"];
    $authUrl .= "&scope=".$_ENV["OAUTH_SCOPES"];
    $authUrl .= "&response_mode=query";

 redirect($authUrl);
?>
Hello