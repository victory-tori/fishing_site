<?php

include_once 'classes/Database.php';
include_once 'classes/Account_manager.php';
include_once 'classes/Record.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function show_acc_data($user) {
    $content = "Twoje dane: <br>";
    $content .= 'Nazwa uytkownika: ' . $user["username"] . '<br>';
    $content .= 'Imie i nazwisko: ' . $user["fullname"] . '<br>';
    $content .= 'Kontakt: ' . $user["email"] . '<br>';
    return $content;
}

$db = new Database("localhost", "root", "", "fishing");
$um = new Login_manager();

$title = "";
$content = "";

$user_id = $um->get_logged_in_user($db, session_id());
$user = $db->get_user_by("users", "user_id", $user_id);
if ($user_id > 0) {
    $title .= "Hello, " . $user["username"] . "!";
    $content .= show_acc_data($user);
    $content .= "Rekordy:<br>";
    $content .= Record::show_records(Record::get_all_records_of($db, "user_id", $user_id, ["record_name", "fish", "date", "weight"]));
    
} else {
    $title .= "Nieuprawniony dostęp!";
    $content .= "Brak dostępu: nie jesteś zalogowany<br>";
}