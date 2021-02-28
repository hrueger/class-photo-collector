<?php
    require_once("./lib.php");

    // Initialize the OAuth client
    $oauthClient = getClient();
  
      $authUrl = $oauthClient->getAuthorizationUrl();
  
      // Save client state so we can validate in callback
      $_SESSION['oauthState'] = $oauthClient->getState();
  
      // Redirect to AAD signin page
      redirect($authUrl);
?>
Hello