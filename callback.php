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

    // load user data
    $data = request('/me?$select=id');
    $statement = $db->prepare("SELECT * FROM users WHERE microsoft_id=?");
    $statement->execute(array($data["id"]));   

    $users = $statement->fetchAll();
    if (count($users) == 0) {
        $data = request('/me?$select=id,department,displayName,jobTitle,mail');
        $_SESSION["job"] = $data["jobTitle"];
        $_SESSION["username"] = $data["displayName"];
        $_SESSION["email"] = $data["mail"];
        $_SESSION["class"] = $data["department"];
        $statement = $db->prepare("INSERT INTO users (microsoft_id, class, photo_state, email, username, job) VALUES (?, ?, ?, ?, ?, ?)");
        $statement->execute(array($data["id"], $data["department"], $PHOTO_STATES["MISSING"], $data["mail"], $data["displayName"], $data["jobTitle"]));
        $_SESSION["photo_state"] = $PHOTO_STATES["MISSING"];
        $_SESSION["id"] = $db->lastInsertId();
    } else {
        $_SESSION["job"] = $users[0]["job"];
        $_SESSION["username"] = $users[0]["username"];
        $_SESSION["email"] = $users[0]["email"];
        $_SESSION["photo_state"] = $users[0]["photo_state"];
        $_SESSION["class"] = $users[0]["class"];
        $_SESSION["id"] = $users[0]["id"];
    }

    redirect("index.php");
}
?>
Error