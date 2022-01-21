<?php
    require_once("classes/Page.php"); 
    $current_page = new Page();
    if (filter_input(INPUT_GET, 'page')) { 
        $page = filter_input(INPUT_GET, 'page'); 
        switch ($page) {
            case 'login_page': $page = 'login_page'; break;
            case 'registration_page': $page = 'registration_page'; break;
            case 'account_page': $page = 'account_page'; break;
            case 'galery': $page = 'galery'; break;
            case 'account': $page = 'account'; break;
            case 'account_actions': $page = 'account_actions'; break;
            case 'statistic': $page = 'statistic'; break;
            default: $page = 'home'; }
    } else { 
        $page = "home";
    }
    $file = "scrypts/" . $page . ".php";
    foreach ($current_page->stylesheets[$page] as $sheet)
        $current_page->set_style($sheet);
    if (file_exists($file)) { 
        require_once($file); 
        $current_page->set_title($title); 
        $current_page->set_content($content); 
        $current_page->show();
    }
    