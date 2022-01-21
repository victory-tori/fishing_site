<?php
include_once 'Database.php'; 
include_once 'Record.php'; 
class Record_manager {
    protected $record;
    protected $filters = [
                'record_name' => ['filter' => FILTER_VALIDATE_REGEXP, 
                            'options' => ['regexp' => '/(?=^.{0,40}$)^[a-zA-Z-]+\s[a-zA-Z-]+$/']],
                'fish_name' => ['filter' => FILTER_VALIDATE_REGEXP, 
                            'options' => ['regexp' => '/^[0-9A-Za-ząęłńśćźżó_]{2,25}$/']],
                'weight' => FILTER_VALIDATE_FLOAT,
                'date' => ['filter' => FILTER_VALIDATE_REGEXP, 
                    'options' => ['regexp' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d_]{8,}$/']],];
    
    function show_form ($form_name, $data, $submit) {
        $content = "<h3>$form_name</h3>
        <form method='post' action='index.php?page=account&category=response&type=record&action=$submit'>
            <table id='form_table'>
                <tbody>";
        foreach ($data as $label => $val) {
            $content .= "<tr><td>$label: </td>";
            foreach ($val as $type => $name)
                $content .= "<td><input type='$type' name='$name'></td></tr>";
        }           
        $content .=  "
                </tbody>
                <tfoot>
                    <tr><td colspan='2'>
                            <input type='submit' value='Podtwierdź' name='submit_record_$submit'>
                            <input type='reset' value='Wyczyść' name='reset'>
                    </td></tr>
                    <tr><td colspan='2'>
                            <a href='index.php?page=account'>Wróć</a>
                    </td></tr>
                </tfoot>
            </table>
        </form>";
        return $content;
    }
    function add_record_form() {
        $data = array (
            "Nazwa rekordu" => array ("text" => "record_name"),
            "Nazwa ryby" => array ("text" => "fish"),
            "Data połowu" => array ("date" => "date"),
            "Waga" => array ("text" => "weigh")
        );
        return $this->show_form("Dodaj rekord", $data, "add");
    }
    function change_record_form() {
        $data = array (
            "Wybierz numer rekordu ktry chcesz usunąć" => array ("text" => "fullname"),
        );        
        return $this->show_form("Edycja", $data, "edit");;
    }
    function delete_record_form($db) {
        $data = array (
            "Obecne hasło" => array ("password" => "passwd"),
            "Nowe hasło" => array ("password" => "new_passwd1"),
            "Powtórz nowe hasło" => array ("password" => "new_passwd2")
        );
        return $this->show_form("Zminia hasła", $data, "passwd");
    }
    function show_records($db) {
        $data = array (
            "Email" => array ("text" => "email"),
            "Hasło: " => array ("password" => "passwd")
        );
        return $this->show_form("Zminia emaila", $data, "email");
    }
    
    function check_field($data, $field) {
        $filtered_data = filter_input($data, $this->filters[$field]);
        $errors = []; 
        foreach ($filtered_data as $key => $val) 
            if ($val === false or $val === NULL) 
                array_push($errors, $key);
        if ($errors === [])
            $this->user = new User ($data['username'], $data['fullname'], $data['email'], $data['passwd'], true);
        else {
            $_SESSION['wrong_data'] = join(', ', $errors) . "<br>";
            $this->user = NULL;
        }
        return $this->user;
    }

    function check_record() {
        $data = filter_input_array(INPUT_POST, $this->filters);
        $errors = []; 
        foreach ($data as $key => $val) 
            if ($val === false or $val === NULL) 
                array_push($errors, $key);
        if ($errors === [])
            $this->record = new Record ($data['user_id'], 
                                        $data['record_name'], 
                                        $data['fish_name'], 
                                        $data['weight'], 
                                        $data['date']);
        else {
            $_SESSION['wrong_data'] = join(', ', $errors) . "<br>";
            $this->record = NULL;
        }
        return $this->record;
    }
}
