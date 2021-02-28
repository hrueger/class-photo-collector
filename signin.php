<?php
    require_once("./lib.php");
    
    redirect($_ENV["OAUTH_AUTHORITY"].$_ENV["OAUTH_AUTHORIZE_ENDPOINT"]."?client_id=".$_ENV["OAUTH_APP_ID"]."&scope=".$_ENV["OAUTH_SCOPES"])."&response_type=json";

    $authUrl = $_ENV["OAUTH_AUTHORITY"].$_ENV["OAUTH_AUTHORIZE_ENDPOINT"];
    $authUrl .= "?client_id=".$_ENV["OAUTH_APP_ID"];
    $authUrl .= "&response_type=code";
    $authUrl .= "&redirect_uri=".$_ENV["OAUTH_REDIRECT_URI"];
    $authUrl .= "&scope=".$_ENV["OAUTH_SCOPES"];
    $authUrl .= "&response_mode=query";
    $authUrl .= "&state=12345";

 redirect($authUrl);
?>
Hello