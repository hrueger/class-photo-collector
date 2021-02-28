<?php
    require_once("./lib.php");
    $_SESSION["loggedin"] = false;
    unset($_SESSION["loggedin"]);
    unset($_SESSION["token"]);
    redirect("index.php");
?>