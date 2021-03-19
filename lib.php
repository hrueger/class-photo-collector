<?php

require_once("./secret.php");
require_once("./db.php");
session_start();

$PHOTO_STATES = [];
$PHOTO_STATES["MISSING"] = 0;
$PHOTO_STATES["UPLOADED"] = 1;
$PHOTO_STATES["ACCEPTED"] = 2;
$PHOTO_STATES["PHOTO_REJECTED"] = 3;
$PHOTO_STATES["PRIVACY_REJECTED"] = 4;
$PHOTO_STATES["BOTH_REJECTED"] = 5;

$PHOTO_STATES_PRETTY = [];
$PHOTO_STATES_PRETTY[$PHOTO_STATES["MISSING"]] = "Fehlt";
$PHOTO_STATES_PRETTY[$PHOTO_STATES["UPLOADED"]] = "Warten auf Überprüfung";
$PHOTO_STATES_PRETTY[$PHOTO_STATES["ACCEPTED"]] = "Akzeptiert";
$PHOTO_STATES_PRETTY[$PHOTO_STATES["PHOTO_REJECTED"]] = "Portraitfoto abgelehnt";
$PHOTO_STATES_PRETTY[$PHOTO_STATES["PRIVACY_REJECTED"]] = "Einverständniserklärung abgelehnt";
$PHOTO_STATES_PRETTY[$PHOTO_STATES["BOTH_REJECTED"]] = "Portraitfoto und Einverständniserklärung abgelehnt";


error_reporting(-1);
ini_set("display_errors", "On");


function redirect($u) {
    header("Location: " . $u);
    exit();
}

function endsWith( $haystack, $needle ) {
   $length = strlen($needle);
   if(!$length) {
       return true;
   }
   return substr( $haystack, -$length ) === $needle;
}

function startsWith($string, $startString) { 
    $len = strlen($startString);
    return (substr($string, 0, $len) === $startString);
}

function onPageActive($url) {
    echo endsWith($_SERVER['REQUEST_URI'], $url) ? ' active' : '';
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

function ensureCanUpload() {
    global $PHOTO_STATES;
    if (!in_array(getMyPhotoState(), array($PHOTO_STATES["MISSING"], $PHOTO_STATES["PHOTO_REJECTED"], $PHOTO_STATES["PRIVACY_REJECTED"], $PHOTO_STATES["BOTH_REJECTED"]))) { redirect("index.php"); }
}

function ensureStudent() {
    if ($_SESSION["job"] !== "Schueler") { redirect("index.php"); }
}

function getMyPhotoState() {
    global $db;
    $statement = $db->prepare("SELECT * FROM users WHERE id=?");
    $statement->execute(array($_SESSION["id"]));   

    $users = $statement->fetchAll();
    if (count($users) == 0) {
        return;
    }
    return $users[0]["photo_state"];
}

function ensureCanViewPhotosOfClass($className) {
    $classes = getClassesFlat();
    $class = $classes[array_search($className, array_column($classes, 0))];
    if (!canViewPhotosOfClass($class)) { redirect("index.php"); }
}

function getMySafeUsername() {
    $safeUsername = str_replace("@allgaeugymnasium.onmicrosoft.com", "", $_SESSION["email"]);
    return str_replace(".", " ", $safeUsername);
}

function getSafeUsername($email) {
    $safeUsername = str_replace("@allgaeugymnasium.onmicrosoft.com", "", $email);
    return str_replace(".", " ", $safeUsername);
}

function canViewPhotosOfClass($class) {
    $u = strtolower(str_replace("@allgaeugymnasium.onmicrosoft.com", "", $_SESSION["email"]));
    if (in_array($u, $_ENV["ADMINS"])) {
        return true;
    }
    return in_array($u, array($class[2], $class[3]));
    
}

function getClasses() {
    $classes = array();    
    $i = 0;
    $jgst = "5";
    if (($h = fopen("grades.csv", "r")) !== FALSE) {
      while (($data = fgetcsv($h, 1000, ",")) !== FALSE)   {
        if (!startsWith($data[0], $jgst)) {
          $i++;
          $jgst = $data[0][0].$data[0][1];
        }
        $classes[$i][] = $data;
      }
      fclose($h);
    }
    return $classes;
}

function getClassesFlat() {
    $classes = array();
    if (($h = fopen("grades.csv", "r")) !== FALSE) {
      while (($data = fgetcsv($h, 1000, ",")) !== FALSE)   {
        $classes[] = $data;
      }
      fclose($h);
    }
    return $classes;
}

function serveFile($filepath) {
    $mime_type = mime_content_type($filepath);
    header('Content-type: '.$mime_type);
    readfile($filepath);
    exit();
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