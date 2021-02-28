<?php

require_once("./secret.php");
session_start();


error_reporting(-1);
ini_set('display_errors', 'On');


function redirect($u) {
    header("Location: " . $u);
}

?>