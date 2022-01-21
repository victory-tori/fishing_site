<?php 
include_once 'classes/Database.php';
include_once 'classes/User.php';
include_once 'classes/Record.php';

    $title = "Rekordy użytkowników"; 
    $content = "";   
    
    $db = new Database ("localhost", "root", "", "fishing"); 
    
    $users = User::get_all_users_from_DB($db, ["user_id", "username"]);
    if ($users != []) {
        foreach ($users as $user) {
            if (Record::get_all_records_of ($db, "user_id", $user["user_id"], ["fish", "weight"])) {
                $content .= $user['username'] . "<br>";
                $content .= Record::show_records(
                    Record::get_all_records_of ($db, "user_id", $user["user_id"], ["fish", "weight"])) . "<br>";   
            }
        }
    } else $content .= "<h3 style='color: red;'>Nie ma rekordów</h3>";
    
            