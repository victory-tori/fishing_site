<?php
include_once 'User.php';

class Registration_form {
    protected $user;
    
    function show_reg_form() {
        return "
        <form method='post' action='index.php?page=registration_page'>
            <table id='form_table'>
                <tbody>
                    <tr>
                        <td>Nazwa użytkownika: </td>
                        <td><input type='text' name='username'></td>
                    </tr>
                    <tr>
                        <td>Imie i nazwisko:</td>
                        <td><input type='text' name='fullname'></td>
                    </tr>
                    <tr>
                        <td>Hasło:</td>
                        <td><input type='password' name='passwd'></td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td><input type='text' name='email'></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr><td colspan='2'>
                            <input type='submit' value='Zarejestruj się' name='submit_register'>
                            <input type='reset' value='Wyczyść' name='reset'>
                    </td></tr>
                </tfoot>
            </table>
        </form>";
    }    
    function check_user() {
        $args = ['username' => ['filter' => FILTER_VALIDATE_REGEXP, 
                            'options' => ['regexp' => '/^[0-9A-Za-ząęłńśćźżó_]{2,25}$/']],
                'fullname' => ['filter' => FILTER_VALIDATE_REGEXP, 
                            'options' => ['regexp' => '/(?=^.{0,40}$)^[a-zA-Z-]+\s[a-zA-Z-]+$/']],
                'passwd' => ['filter' => FILTER_VALIDATE_REGEXP, 
                            'options' => ['regexp' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d_]{8,}$/']],
                'email' => FILTER_VALIDATE_EMAIL];
        $data = filter_input_array(INPUT_POST, $args);
        $errors = []; 
        foreach ($data as $key => $val) 
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
}