<pre><?php
require_once("./lib.php");
if (isset($_REQUEST["error"])) {
    echo "<pre>".$_REQUEST["error"]."</pre>";
    if (isset($_REQUEST["error_description"])) {
        echo "<pre>".$_REQUEST["error_description"]."</pre>";
    }
    if (isset($_REQUEST["error_uri"])) {
        echo "<pre>".$_REQUEST["error_uri"]."</pre>";
    }
    exit();
} else if (isset($_REQUEST["code"])) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$_ENV["OAUTH_AUTHORITY"].$_ENV["OAUTH_TOKEN_ENDPOINT"]);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,
    "grant_type=authorization_code&client_id=".$_ENV["OAUTH_APP_ID"]."&redirect_uri=".$_ENV["OAUTH_REDIRECT_URI"]."&code=".$_REQUEST["code"]."&client_secret=".urlencode($_ENV["OAUTH_APP_SECRET"]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $server_output = curl_exec($ch);
    curl_close ($ch);
    $jsonoutput = json_decode($server_output, true);
    $bearertoken = $jsonoutput['access_token'];

    $_SESSION["token"] = $bearertoken;
    $_SESSION["loggedin"] = true;
    redirect("index.php");
}
?>
Error