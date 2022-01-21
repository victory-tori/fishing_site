<?php

class Database {
    private $mysqli;
    public function __construct($serwer, $user, $pass, $base) { 
        $this->mysqli = new mysqli ($serwer, $user, $pass, $base);
        if ($this->mysqli->connect_errno) { 
            printf("Nie udało sie połączenie z serwerem: %s\n", $this->mysqli->connect_error);
            exit();
        } 
        if ($this->mysqli->set_charset("utf8")) {}
    }
    function __destruct() { 
        $this->mysqli->close();
    }
    
    public function select_as_array ($sql, $fields) {
        $data = [];
        $fields_number = count($fields);
        if ($this->mysqli->query($sql)) {
            $result = $this->mysqli->query($sql);
            while ($row = $result->fetch_object()) {
                $record = [];
                for ($i = 0; $i < $fields_number; $i++) { 
                    $p = $fields[$i]; 
                    $record[$p] = $row->$p;
                }
                array_push($data, $record);
            } 
            $result->close();
        }
        return $data;
    }
    public function select($table, $fields) {
        $sql = "SELECT " . join(', ', $fields) . " FROM $table";
        return $this->select_as_array ($sql, $fields);
    } 
    public function select_where($table, $id_name, $id, $fields) {
        $sql = "SELECT " . join(', ', $fields) . " FROM $table WHERE $id_name='$id'";
        return $this->select_as_array ($sql, $fields);
    }
    
    
    
    public function insert ($table, $data, $keys) {
        $sql = "INSERT INTO $table ";
        if ($keys) $sql .= '(' . join(", ", array_keys($data)) . ') '; 
        $sql .= "VALUES ('" . join("', '", array_values($data)) . "')";
        if( $this->mysqli->query($sql)) return true;
        else return false;
    }
    public function delete($table, $id_name, $id) {
        $sql = "DELETE FROM $table WHERE $id_name = '$id'";
        if( $this->mysqli->query($sql)) return true;
        else return false;
    }
    public function find ($sql, $id) {
        return $this->mysqli->query($sql . $id . "'");
    }
    public function find_where ($table, $id_name, $id) {
        $sql = "SELECT * FROM $table WHERE $id_name='" . $id . "'";
        return $this->mysqli->query($sql);
    }
    public function update_where($table, $data, $id_name, $id) {
        $sql = "UPDATE $table SET ";
        $sql .= implode(", ", array_map(
            function($key, $value) {
                return $key . " = '" . $value . "'";
            }, array_keys($data), array_values($data)
        ));
        $sql .= " WHERE $id_name='" . $id . "'";
        return $this->mysqli->query($sql);
    }
    public function get_mysqli() { 
        return $this->mysqli;
    }
    
    // sprzwdza poprawność hasła dla użytkownika,
    // wyszykanego przez dane w $data
    public function verify_password ($tabela, $data, $passwd) {
        $id = -1; 
        $sql = "SELECT * FROM $tabela WHERE ".$data["by"]. "='".$data["val"]."'";
        if ($result = $this->mysqli->query($sql)) {
            $ile = $result->num_rows; 
            if ($ile == 1) { 
                $row = $result->fetch_object();
                $hash = $row->passwd; 
                if (password_verify($passwd, $hash))
                    $id = $row->user_id;
            }
        } 
        return $id;
    }
    // sprawdza czy użytkownik o podanych parametrach istnieje
    // zwraca "błedy" lub ciąg pusty
    public function if_user_exist ($table, $data) {
        $errors = [];
        $problems = [
            "username" => "Użytkownik o podanej nazwie uytkownika już istnieje.", 
            "email" => "Użytkownik z podanym email już istnieje."
        ];
        foreach ($data as $field => $val)             
            if ($result = $this->find_where($table, $field, $val)) {
                $ile = $result->num_rows; 
                if ($ile == 1) 
                    array_push($errors, $problems[$field]);
            }
        return join('<br>', $errors);
    }
    
    public function get_user_by ($tabel, $id_name, $id) {
        $sql = "SELECT * FROM $tabel WHERE $id_name='$id'";
        $user = NULL;
        if ($result = $this->mysqli->query($sql)) {
            $ile = $result->num_rows; 
            if ($ile == 1) { 
                $row = $result->fetch_object(); 
                $user = array (
                    "fullname" => $row->fullname,
                    "username" => $row->username,
                    "email" => $row->email,
                    "passw" => $row->passwd,
                );
            }
        } 
        return $user;
    }
}

