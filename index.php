<?php
require_once("lib.php");
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"]) {
    $url = "https://graph.microsoft.com/v1.0/me";
    $ch = curl_init($url);
    $User_Agent = 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.43 Safari/537.31';
    $request_headers = array();
    $request_headers[] = 'User-Agent: '. $User_Agent;
    $request_headers[] = 'Accept: application/json';
    $request_headers[] = 'Authorization: Bearer '. $_SESSION["token"];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $server_output = curl_exec($ch);
    curl_close ($ch);
    $jsonoutput = json_decode($server_output, true);

    echo "Welcome, ".$jsonoutput["displayName"].". You are a ".$jsonoutput["jobTitle"];
    ?>
    <a href="signout.php">Sign out</a>
    <?php
} else {
?>
<a href="signin.php">Sign in</a>
<?php } ?>