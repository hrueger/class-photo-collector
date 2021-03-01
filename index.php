<?php
require_once("lib.php");
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"]) {
    $jsonoutput = request("/me");

    echo "Welcome, ".$jsonoutput["displayName"].". You are a ".$jsonoutput["jobTitle"];
    ?>
    <a href="signout.php">Sign out</a>
    <?php
} else {
?>
<a href="signin.php">Sign in</a>
<?php } ?>