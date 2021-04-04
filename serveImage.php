<?php
require_once("lib.php");
require_once("class.Thumbnail.php");
ensureLoggedin();
ensureTeacher();

if (!isset($_GET["type"]) || !in_array($_GET["type"], array("photo", "privacy", "teacher")) || (!isset($_GET["userId"]) && !isset($_GET["username"]))) {
 exit();
}

if ($_GET["type"] !== "teacher") {
    $statement = $db->prepare("SELECT * FROM users WHERE id=?");
    $statement->execute(array($_GET["userId"]));
    $users = $statement->fetchAll();
}

if ($_GET["type"] == "teacher" || ($users && isset($users[0]))) {
    if ($_GET["type"] == "teacher") {
        $path = "userdata/" . $_GET["class"] . "/Lehrkraefte/" . $_GET["username"] . ".";
    } else {
        $path = "userdata/" . $users[0]["class"] . "/" .getSafeUsername($users[0]["email"]) . " " . ($_GET["type"] == "photo" ? "Foto" : "Einverstaendniserklaerung") . ".";
    }
    foreach (array("png", "jpg", "jpeg", "gif") as $extension) {
        $fullpath = $path . $extension;
        if (is_file($fullpath)) {
            if (isset($_GET["thumbnail"])) {
                $thumbnail = new Thumbnail($fullpath, 100);
                $thumbnail->show();
            } else {
                serveFile($fullpath);
            }
        }
    }
}

?>