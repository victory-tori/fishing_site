<?php

class User { 
    const STATUS_USER = 1; 
    const STATUS_ADMIN = 2; 
    
    protected $username;
    protected $passwd;
    protected $fullname;
    protected $email;
    protected $date;
    protected $status;
            
    function __construct($username, $fullname, $email, $passwd){
        $this->status = User::STATUS_USER;
        $this->username = $username;
        $this->fullname = $fullname;
        $this->email = $email;
        $this->passwd = password_hash($passwd, PASSWORD_DEFAULT);
        $this->date = (new DateTime())->format('Y-m-d');
    }
    
    function to_array () {
        return [
            "username" => $this->username,
            "fullname" => $this->fullname,
            "email" => $this->email,
            "passwd" => $this->passwd,
            "status" => $this->status,
            "date" => $this->date
        ];
    }

    function save_user_to_DB($db) {
        $data = $this->to_array();
        $content = "";
        $response = $db->if_user_exist("users", ["username" => $this->username, 
                                                 "email" => $this->email]);
        if ($response) $content .= $response;
        else if ($db->insert("users", $data, true)) 
            $content .= "ok";
        else
            $content .= "error";
        return $content;
    }
    static function get_all_users_from_DB($db, $fields) {
        return $db->select("users", $fields);
    }   
}