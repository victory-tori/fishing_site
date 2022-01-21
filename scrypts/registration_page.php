<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once "classes/Database.php";
include_once "classes/User.php";
include_once "classes/Registration_form.php";

$db = new Database("localhost", "root", "", "fishing"); 
$rf = new Registration_form();
$title = "Rejestracja";
$content = $rf->show_reg_form();

if (filter_input(INPUT_POST,'submit_register', FILTER_SANITIZE_FULL_SPECIAL_CHARS)) {
    $user = $rf->check_user();
    if ($user === NULL) $_SESSION['message'] =  "wrong data";
    else $_SESSION['message'] = $user->save_user_to_DB($db);

    header('location: index.php?page=registration_page');
    exit;
}

if(isset($_SESSION['message'])) {
    switch ($_SESSION['message']) {
        case "email": $content .= "<p class='form_error'>Podany email już jest wykorzystywany przez innego użytkownika, wybierz inny</p>"; break;
        case "username": $content .= "<p class='form_error'>Uzytkownik o takiej nazwie użytkownika już istnieje, wybierż inną nazwę użytkownika</p>"; break;
        case "wrong data": $content .= "<p class='form_error'>Niepoprawne dane rejestracji<br>".$_SESSION['wrong_data']."</p>"; unset($_SESSION['wrong_data']); break;
        case "ok": $content = "Poprawnie zarejestrowałeś się! Teraz możesz zalogować się.<br><a class='go_back' href='index.php?page=login_page'>Zaloguj się</a>"; break;
        default: $content .= "<p class='form_error'>ERROR";
    }
    unset($_SESSION['message']);
    if ( isset(INPUT_COOKIE[session_name()]) ) { 
        setcookie(session_name(),'', time() - 42000, '/');
    }
    session_destroy();
}

