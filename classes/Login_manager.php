<?php
include_once 'User.php';

class Login_manager {
    function login_form() { 
        return " 
        <form action='index.php?page=login_page' method='post'>
            <table class='form_table'>
                <tbody>
                    <tr>
                        <td>Login: </td>
                        <td><input type='text' name='login'></td>
                    </tr>
                    <tr>
                        <td>Hasło:</td>
                        <td><input type='password' name='passwd'></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr><td colspan='2'>
                        <input type='submit' value='Zaloguj' name='do_login' /> 
                        <input type='reset' value='Wyczyść' name='reset'>
                    </td></tr>
                </tfoot>
            </table>
        </form>";            
    } 
    function login($db) {
        $args = [
            'login' => FILTER_SANITIZE_ADD_SLASHES, 
            'passwd' => FILTER_SANITIZE_ADD_SLASHES
        ];
        $data = filter_input_array(INPUT_POST, $args); 
        $login = $data["login"]; 
        $passwd = $data["passwd"];  
        $user_id = $db->verify_password("users", ["by"=>"username", 
                                                   "val"=>$login], 
                                                    $passwd);
        if ($user_id >= 0) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $db->delete("logged_in_users", "user_id", $user_id);
            $data_table = [session_id(), $user_id, (new DateTime())->format('Y-m-d')];
            $db->insert("logged_in_users", $data_table, false);
            
            header("location:index.php?page=account_page");
        } 
        return $user_id;
    } 
    function logout($db) { 
        session_start();
        
        $db->delete("logged_in_users", "session_id", session_id());
        if ( isset(INPUT_COOKIE[session_name()]) ) { 
            setcookie(session_name(),'', time() - 42000, '/');
        }
        session_destroy();
        header('location:index.php?page=home');
    } 
    function get_logged_in_user($db, $session_id) { 
        $id = -1;
        if ($result = $db->find_where("logged_in_users", "session_id", $session_id)) {
            $ile = $result->num_rows; 
            if ($ile == 1) { 
                $row = $result->fetch_object();
                $id = $row->user_id;
            }
        } 
        return $id;
    }
}
