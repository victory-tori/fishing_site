<?php

include_once 'classes/Database.php'; 
include_once 'classes/User.php'; 
include_once 'classes/Record.php'; 
include_once 'classes/Login_manager.php';
include_once 'classes/Account_manager.php';

$account_menu = [
    "Edytuj dane" => [
            "Zmień nazwę użytkownika" => "index.php?page=account&action=change_username",
            "Zmień imię i nazwisko" => "index.php?page=account&action=change_fullname",
            "Zmień hasło" => "index.php?page=account&action=change_passwd",
            "Zmień email" => "index.php?page=account&action=change_email"],
    "Rekordy" => [
        "Dodaj rekord" => "index.php?page=account&action=add_record",
        "Edytuj rekord" => "index.php?page=account&action=edit_record",
        "Usuń rekord" => "index.php?page=account&action=delete_record"]
];
function get_account_menu($account_menu) {
    $menu = "<form method='post' action='index.php?page=account' id='account_menu'>";
    foreach ($account_menu as $block => $option) {
        $menu .= "<fieldset class='account_menu'>";
        $menu .= "<legend>$block</legend>";
        foreach ($option as $name => $url)
            $menu .= '<a href="'.$url.'"><h5>'.$name.'</h5></a>';
        
        $menu .= "</fieldset>";
    }
    return $menu;
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$title = "Konto";
$content = "";

$db = new Database("localhost", "root", "", "fishing");
$um = new Login_manager();
$am = new Account_manager();

$user_id = $um->get_logged_in_user($db, session_id());

if ($user_id > 0) {
    if (filter_input(INPUT_GET, "action")){
        $action = filter_input(INPUT_GET, "action");
        $content .= $am->show_form($action);
        if ($action == "edit_record" || $action == "delete_record") {
            $table = Record::show_records(Record::get_all_records_of($db, "user_id", $user_id, ["record_name", "fish", "date", "weight"]));
            if ($table == null) $content = "<p class='form_error'>Tablica rekordów jest pusta</p>.<a href='index.php?page=account' class='go_back'>Wróć</a>";
            else $content .= $table;            
        }
        
    } else $content = get_account_menu($account_menu);
    if (filter_input(INPUT_POST,"submit", FILTER_SANITIZE_FULL_SPECIAL_CHARS)) {
        $submit = filter_input(INPUT_POST,"submit", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        if ($submit == "Edytuj rekord") $data = $am->check_data_edit();        
        else $data = $am->check_data();
        
        if (is_string($data))
            $content .= "<p class='form_error'> Niepoprawne dane: $data.<br>Spróbuj ponownie</p>";
        else if (array_key_exists("passwd", $data)) {
                if ($db->verify_password("users", ["by"=>"user_id", "val"=>$user_id], $data["passwd"]) >=0)
                    $content .= $am->edit_data($db, $user_id, $data); 
                else $content .= "<p class='form_error'>Złe hasło!</p>";
        } else switch ($submit) { 
            case "Dodaj rekord": 
                $data["user_id"] = $user_id;
                $record = new Record($data);
                if ($record->save_record_to_DB($db)) $content .= "DODANO";
                else $content .= "NIE DODANO";
                break;
            case "Edytuj rekord": 
                $rec_to_edit = $data['rec_to_edit'];
                unset($data['rec_to_edit']); 
                $record_id = $am->get_record_id($db, $user_id, $rec_to_edit);
                if ($record_id != null)
                    if ($db->update_where("records", $data, "record_id", )) 
                        $content .= "Dane zostały zmienione! ";
                    else $content .= "<p class='form_error'>Coś nie poszło<p>";
                else $content .= "<p class='form_error'>Zły numer rekordu</p>";
                break;
            case "Usuń rekord": 
                $rec_to_delete = $data['rec_to_delete'];
                unset($data['rec_to_edit']);   
                $record_id = $am->get_record_id($db, $user_id, $rec_to_delete);
                if ($record_id != null) 
                    if ($db->delete("records", "record_id", $record_id)) 
                        $content .= "Dane zostały zmienione! ";
                    else $content .= "<p class='form_error'>Coś nie poszło</p>";
                else $content .= "<p class='form_error'>Zły numer rekordu</p>";
                break;
        }
            
    }
} else {
    $content .= "Brak dostępu: nie jesteś zalogowany<br>";
}