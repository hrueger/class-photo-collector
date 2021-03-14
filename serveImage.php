<?php
require_once("lib.php");
ensureLoggedin();
// ensureTeacher();

if (!isset($_GET["type"]) || !in_array($_GET["type"], array("photo", "privacy")) || !isset($_GET["userId"])) {
 exit();   
}

$statement = $db->prepare("SELECT * FROM users WHERE id=?");
$statement->execute(array($_GET["userId"]));
$users = $statement->fetchAll();

if ($users && isset($users[0])) {
    $path = "userdata/" . $users[0]["class"] . "/" .getSafeUsername($users[0]["email"]) . " " . ($_GET["type"] == "photo" ? "Foto" : "Einverstaendniserklaerung") . ".";
    foreach (array("png", "jpg", "jpeg", "gif") as $extension) {
        $fullpath = $path . $extension;
        if (is_file($fullpath)) {
            serveFile($fullpath);
        }
    }
}

?>