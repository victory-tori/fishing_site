<?php
    include_once 'classes/Database.php'; 
    include_once 'classes/User.php'; 
    include_once 'classes/Login_manager.php'; 

    $db = new Database("localhost", "root", "", "fishing");
    $um = new Login_manager();
    
    $title = "Logowanie";
    $content = "";
    
    if (filter_input(INPUT_GET, "action") == "do_logout") { 
        $um->logout($db);
    }
    if (filter_input(INPUT_POST, "do_login")) {
        $user_id = $um->login($db);
        if ($user_id == -1) {
            $content .= $um->login_form();
            $content .= "<p class='form_error'>Błędna nazwa użytkownika lub hasło</p>"; 
        }
    } else $content .= $um->login_form();

