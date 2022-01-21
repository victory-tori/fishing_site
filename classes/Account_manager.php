<?php
include_once 'User.php';

class Account_manager {
    protected $filters = [
        'username' => [
            'filter' => FILTER_VALIDATE_REGEXP, 
            'options' => ['regexp' => '/^[0-9A-Za-ząęłńśćźżó_]{2,25}$/']],
        'fullname' => [
            'filter' => FILTER_VALIDATE_REGEXP, 
            'options' => ['regexp' => '/(?=^.{0,40}$)^[a-zA-Z-]+\s[a-zA-Z-]+$/']],
        'passwd' => [
            'filter' => FILTER_VALIDATE_REGEXP, 
            'options' => ['regexp' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d_]{8,}$/']],
        'new_passwd1' => [
            'filter' => FILTER_VALIDATE_REGEXP, 
            'options' => ['regexp' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d_]{8,}$/']],
        'new_passwd2' => [
            'filter' => FILTER_VALIDATE_REGEXP, 
            'options' => ['regexp' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d_]{8,}$/']],
        'email' => FILTER_VALIDATE_EMAIL,
        "record_name" => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        "fish" => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        "date" => [
            'filter' => FILTER_VALIDATE_REGEXP,
            'options' => ['regexp' => '/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/']],
        "weight" => FILTER_VALIDATE_FLOAT,
        "rec_to_edit" => [
            'filter' => FILTER_VALIDATE_INT,
            'options' => ['min_range' => 1]
        ],
        "rec_to_delete" => [
            'filter' => FILTER_VALIDATE_INT,
            'options' => ['min_range' => 1]
        ],
    ];
    protected $dictionary = [
        'username' => "nazwa użytkownika",
        'fullname' => "imię i nazwisko",
        'passwd' => "hasło",
        'new_passwd1' => "nowe hasło",
        'new_passwd2' => "powtórka nowego hasła",
        'email' => "email",
        "record_name" => "nazwa rekordu",
        "fish" => "ryba",
        "date" => "data",
        "weight" => "waga",
        "rec_to_edit" => "numer edytowanego rekordu",
        "rec_to_delete" => "numer rekordu do usunięcia"
    ];
    protected $forms = [
        "change_username" => [
            "submit" => "Zmień nazwę użytkownika",
            "form_name" => "Zmiana nazwy uytkownika", 
            "data" => [
                "Nazwa użytkownika" => ["text" => "username"],
                "Hasło" => ["password" => "passwd"]
            ]],
        "change_fullname" => [
            "submit" => "Zmień imię i nazwisko", 
            "form_name" => "Zmiana imienia i nazwiska", 
            "data" => [
                "Imie i nazwisko" => ["text" => "fullname"],
                "Hasło: " => ["password" => "passwd"]
        ]], 
        "change_passwd" => [
            "submit" => "Zmień hasło",
            "form_name" => "Zmiana hasła", 
            "data" => [
                "Obecne hasło" => ["password" => "passwd"],
                "Nowe hasło" => ["password" => "new_passwd1"],
                "Powtórz nowe hasło" => ["password" => "new_passwd2"]
        ]],
        "change_email" => [
            "submit" => "Zmień email",
            "form_name" => "Zmiana skrzynki pocztowej", 
            "data" => [
                "Email" => ["email" => "email"],
                "Hasło: " => ["password" => "passwd"]
        ]],
        "add_record" => [
            "submit" => "Dodaj rekord",
            "form_name" => "Dodanie rekordu", 
            "data" => [
                "Nazwa rekordu" => ["text" => "record_name"],
                "Nazwa ryby" => ["select" => "fish"],
                "Data połowu" => ["date" => "date"],
                "Waga" => ["text" => "weight"]
            ]],
        "edit_record" => [
            "submit" => "Edytuj rekord",
            "form_name" => "Edycja rekordu", 
            "data" => [
                "Numer rekordu który chcesz zmienić" => ["number" => "rec_to_edit"],
                "Nazwa rekordu" => ["text" => "record_name"],
                "Nazwa ryby" => ["select" => "fish"],
                "Data połowu" => ["date" => "date"],
                "Waga" => ["text" => "weight"]
            ]],
        "delete_record" => [
            "submit" => "Usuń rekord",
            "form_name" => "Usunięcie rekordu", 
            "data" => [
                "Wybierz numer rekordu który chcesz usunąć" => ["text" => "rec_to_delete"]
            ]]
    ];
    protected $fishes = [
        "", "płoć","leszcz","krąp","karp","karaś","kleń","jaź",
        "certa","lin","okoń","sandacz","szczupak","węgorz",
        "wstrzęga","sum","miętus","inna"];

    // zwraca formularz
    function show_form ($form) {
        $form_data = $this->forms[$form];
        $content = "<h2 class='acc_form_name'>" . $form_data['form_name'] . "</h2>
        <form method='post' action='index.php?page=account&action=$form'>
            <table class='form_table'>
                <tbody>";
        foreach ($form_data['data'] as $label => $val) {
            $content .= "<tr><td>$label: </td><td>";
            foreach ($val as $type => $name)
                if ($type == "select") {
                    $content .= "<select name='$name' id='$name'>";
                    foreach ($this->fishes as $fish_name)
                       $content .= "<option value=$fish_name>$fish_name</option>";
                    $content .=  "</select";
                } else
                    $content .= "<input type='$type' name='$name'";
            if ($type == "date") $content .= " max='2022-01-19'";
            $content .= "></td></tr>";
        }           
        $content .=  "
                </tbody>
                <tfoot>
                    <tr><td colspan='2'>
                            <input type='submit' value='".$form_data['submit']."' name='submit'>
                            <input type='reset' value='Wyczyść' name='reset'>
                    </td></tr>
                    <tr><td colspan='2'>
                            <a href='index.php?page=account' class='go_back'>Wróć</a>
                    </td></tr>
                </tfoot>
            </table>
        </form>";
        return $content;
    }
    // zwraca filtr zgodnie z tymi parametrami w INPUT_POST
    function filter_assembly () {
        $array_keys = array_keys(filter_input_array(INPUT_POST));
        unset($array_keys[array_search("submit", $array_keys)]);
        $filter = [];
        foreach ($array_keys as $key)
            if (array_key_exists($key, $this->filters)) 
                $filter["$key"] = $this->filters["$key"];
        return $filter;
    }
    // sprzwdza poprawność wprowadzonych danych
    function check_data() {
        $filter = $this->filter_assembly();
        $data = filter_input_array(INPUT_POST, $filter);
        $errors = []; 
        foreach ($data as $key => $val) 
            if ($val === false or $val === NULL or $val == "") 
                array_push($errors, $this->dictionary[$key]);
        if ($errors == []) return $data;
                   
        else return join(', ', $errors);
    }
    function check_data_edit () {
        $unfil_data = filter_input_array(INPUT_POST);
        $data = [];
        foreach ($unfil_data as $key => $val) 
            if (!($val === false || $val === null || $val === "")) 
                $data[$key] = $val;
        unset($data['submit']);    
        if (!array_key_exists("rec_to_edit", $data)) return "Nie wpisałeś numer rekurdu.";
        if (count($data) <= 1) return "Nic nie wpisałeś do zmiany"; 
        
        $filter = [];
        foreach (array_keys($data) as $key)
            if (array_key_exists($key, $this->filters))
                $filter[$key] = $this->filters[$key];
        $data = filter_var_array($data, $filter);
        $errors = [];
        foreach ($data as $key => $val) 
            if ($val === false || $val === null) 
                array_push($errors, $this->dictionary[$key]);
        
        if ($errors == []) return $data;           
        else return join(', ', $errors);
    }    
    // zmienia dane użytkownika
    function edit_data ($db, $user_id, $data) {
        $content = "";
        $to_cheak = [];
        
        foreach (["username","email"] as $key) 
            if (array_key_exists($key, $data))
                $to_cheak["$key"] = $data[$key];
            
        if ($to_cheak != [] && $db->if_user_exist ("users", $to_cheak) != "") 
            $content = $db->if_user_exist ("users", $to_cheak);
        else if (array_key_exists("new_passwd1", $data)) 
            if ($data["new_passwd1"] != $data["new_passwd2"]) 
                $content = "Wrong pass";        
        
        if ($content == ""){
                if (array_key_exists("new_passwd1", $data)) {
                    $data["passwd"] = password_hash($data["new_passwd1"], PASSWORD_DEFAULT);
                    unset($data["new_passwd1"]);
                    unset($data["new_passwd2"]);
                }
                else unset($data["passwd"]);
                if ($db->update_where("users", $data, "user_id", $user_id))
                    $content .= "Zmieniono!";
            else $content .= "Zapisywanie nie powiodło się =(.";
        } 
        return $content;
    }
    function get_record_id ($db, $user_id, $rec_to) {
        $records = Record::get_all_records_of($db, "user_id", $user_id, ["record_id"]);
        if (array_key_exists($rec_to-1, $records))
            $record_id = $records[$rec_to-1]["record_id"];
        else return null;
        if ($db->find_where("records", "record_id", $record_id))
            return $record_id;
        else return null;
    }
}
