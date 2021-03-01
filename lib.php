<?php

require_once("./secret.php");
require_once("./db.php");
session_start();

$PHOTO_STATES = [];
$PHOTO_STATES["MISSING"] = 0;
$PHOTO_STATES["UPLOADED"] = 1;
$PHOTO_STATES["ACCEPTED"] = 2;
$PHOTO_STATES["REJECTED"] = 3;


error_reporting(-1);
ini_set("display_errors", "On");


function redirect($u) {
    header("Location: " . $u);
    exit();
}

function onPageActive($url) {
    echo str_ends_with($_SERVER['REQUEST_URI'], $url) ? ' active' : '';
}

function isLoggedin() {
    return (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"]);
}

function ensureLoggedin() {
    if (!isLoggedin()) { redirect("index.php"); }
}

function ensureTeacher() {
    if ($_SESSION["job"] !== "Lehrer") { redirect("index.php"); }
}

function ensureStudent() {
    if ($_SESSION["job"] !== "Schueler") { redirect("index.php"); }
}

function request($url) {
    $ch = curl_init("https://graph.microsoft.com/v1.0/".$url);
    $User_Agent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:86.0) Gecko/20100101 Firefox/86.0";
    $request_headers = array();
    $request_headers[] = "User-Agent: ". $User_Agent;
    $request_headers[] = "Accept: application/json";
    $request_headers[] = "Authorization: Bearer ". $_SESSION["token"];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $server_output = curl_exec($ch);
    curl_close ($ch);
    return json_decode($server_output, true);
}

function var_pre_dump($d) {
    echo "<pre>";
    var_dump($d);
    echo "</pre>";
}

function random_str(
    int $length = 64,
    string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
): string {
    if ($length < 1) {
        throw new \RangeException("Length must be a positive integer");
    }
    $pieces = [];
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $pieces []= $keyspace[random_int(0, $max)];
    }
    return implode('', $pieces);
}

?>