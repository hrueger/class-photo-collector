<?php 
$db = new PDO('sqlite:database.sqlite3');
// $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$db->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY, 
    microsoft_id TEXT,
    username TEXT,
    email TEXT,
    class TEXT,
    job TEXT,
    photo_state INTEGER)");
?>