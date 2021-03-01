<pre><?php
require_once("./lib.php");

// Check for errors and display them
if (isset($_REQUEST["error"])) {
    echo "<pre>".$_REQUEST["error"]."</pre>";
    if (isset($_REQUEST["error_description"])) {
        echo "<pre>".$_REQUEST["error_description"]."</pre>";
    }
    if (isset($_REQUEST["error_uri"])) {
        echo "<pre>".$_REQUEST["error_uri"]."</pre>";
    }
    exit();

// if everythink worked, we get a code and the state
} else if (isset($_REQUEST["code"])) {
    // state must match the stored state
    if (!isset($_REQUEST["state"]) || $_REQUEST["state"] !== $_SESSION["state"]) {
        print "error: state does not exist or match";
        exit();
    }
    // get the jwt token by using the auth code
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,"https://login.microsoftonline.com/".$_ENV["OAUTH_TENANT_ID"]."/oauth2/v2.0/token");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,
    "grant_type=authorization_code&client_id=".$_ENV["OAUTH_APP_ID"]."&redirect_uri=".$_ENV["OAUTH_REDIRECT_URI"]."&code=".$_REQUEST["code"]."&client_secret=".urlencode($_ENV["OAUTH_APP_SECRET"]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec($ch);
    curl_close ($ch);
    $jsonoutput = json_decode($server_output, true);
    $bearertoken = $jsonoutput["access_token"];

    // store the token and redirect to index.php
    $_SESSION["token"] = $bearertoken;
    $_SESSION["loggedin"] = true;
    redirect("index.php");
}
?>
Error