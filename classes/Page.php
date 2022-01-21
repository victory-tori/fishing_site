<?php
include_once 'classes/Database.php'; 
include_once 'classes/User.php'; 
include_once 'classes/Login_Manager.php';

class Page {
    protected $content; 
    protected $title = "Projekt zaliczeniowy";
    protected $keywords = "narzędzia internetowe, php, formularz, sql, projekt";
    protected $buttons = [
        "left_buttons" => [
            "Strona główna" => "?page=home",
            "Galeria" => "?page=galery", 
            "Statystyki" => "?page=statistic"],
        "login_buttons" => [
            "Zaloguj się" => "?page=login_page",
            "Zarejestruj się" => "?page=registration_page"],
        "logged_in_buttons" => [
            "Konto" => "?page=account_page",
            "Zarządzaj kontem" => "?page=account",
            "Wyloguj się" => "?page=login_page&action=do_logout"],
        "aside_links" => [
            "Sklepy wędkarskie" => [
                "Fishing store" => "https://www.fishingstore.pl/",
                "Fishing Mart" => "https://www.fishing-mart.com.pl/pl2",
                "E-fishing" => "https://www.e-fishing.pl"],
            "Przydatne strony" => [
                "Zalety wędkarstwo" => "http://www.rybobranie.pl/wedkarstwo-hobby-ktore-ma-same-zalety/",
                "Atlas ryb" => "https://www.pzw.org.pl/linczubylublin/cms/10697/atlas_ryb",
                "Ryby chronione w Polsce" => "https://www.pzw.org.pl/deblin/cms/16480/ryby_prawnie_chronione_w_polsce",
                "Okresy ochronne ryb" => "https://www.pzw.org.pl/stelmet/cms/21565/wymiary_i_okresy_ochronne_ryb"]]];
    public $stylesheets = [
        "account" => ["forms", "tables"], 
        "account_page" => ["tables"],
        "galery" => ["galery"],
        "home" => ["home"],
        "login_page" => ["forms", "tables"], 
        "registration_page" => ["forms", "tables"], 
        "statistic" => ["tables"]];



    public function set_title($new_title) { 
        $this->title = $new_title;
    }
    public function set_content($new_content) { 
        $this->content = "<article>" . $new_content. "</article>";
    }
    public function set_key_words($new_words) { 
        $this->keywords = $new_words;
    } 
    public function set_style($url) { 
        echo '<link rel="stylesheet" href="css/' . $url . '.css" type="text/css"/>';
    }
    
    
    
    public function show() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->show_header(); 
        $this->show_content();
        $this->show_footer();
    } 
    public function show_title() { 
        echo "<title>$this->title</title>";
    } 
    public function show_keywords() { 
        echo "<meta name=\"keywords\" contents=\"$this->keywords\">"; 
    }
    public function show_banner() {
        echo "<nav class='banner'>";
        for ($i = 1; $i <=4; $i++) {
            echo "<img class='banner_photo' src='img/banner/banner_$i.jpg' alt='banner_$i' />";
        }          
        echo "</nav>";
    }
    public function show_menu() { 
        echo "<nav class='menu'>".
             "<div class='dropdown_menu'>" .
             "<a href='#'><h3>Menu</h3></a>" .
             "<ul>";
        foreach($this->buttons["left_buttons"] as $name => $url)
            echo '<li><a href="' . $url . '"><h3>' . $name . '</h3></a></li>';
        echo "</div></ul>";
        
        echo "<div style='flex-grow: 1'></div>";
         
        $db = new Database("localhost", "root", "", "fishing");
        $um = new Login_manager();
        $user_id = $um->get_logged_in_user($db, session_id());
        
        $right_buttons = array("" => "");
        if ($user_id > 0) $right_buttons = $this->buttons["logged_in_buttons"];
        else  $right_buttons = $this->buttons["login_buttons"];
        foreach($right_buttons as $name => $url)
            echo '<a href="' . $url . '"><h3>' . $name . '</h3></a>';
        echo "</nav>";
    }
    public function show_header() { 
        ?> 
        <!DOCTYPE html> 
        <html> 
            <head> 
                <meta charset="UTF-8"> 
                <meta name="viewport" content="width=device-width, initialscale=1.0"> 
                <?php
                $this->set_style('style');
                //$this->set_style('css/'.$this->title.'.css');
                
                echo "<link rel='icon' type='image/x-icon' href='img/icon.png' />";
                echo "<title>".$this->title."</title></head><body>";
    } 
    public function show_content() {
        $this->show_banner(); 
        echo "<header>";
        $this->show_menu();
        echo "<h2>".$this->title."</h2>";
        echo "</header>";
        echo "<main>"; 
        echo $this->content;
        $this->show_aside();
        echo "</main>";
    }
    public function show_aside () {
        echo "<aside>";                                                                         
        foreach ($this->buttons["aside_links"] as $name => $links) {
            echo "<div class='box'> ";
            echo "<h2>$name</h2>";
            foreach ($links as $link_name => $link) {
                echo "<a href='$link'><h3>$link_name</h3></a>";
            }
            echo "</div>";
        }                                                                      
        echo "</aside>";
    }
    public function show_footer() { 
        echo "<footer>&copy; Barysevich Victoia's project </footer>"; 
        echo '</body></html>';
    }
}
