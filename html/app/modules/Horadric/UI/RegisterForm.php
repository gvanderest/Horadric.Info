<?php
/**
 * Horadric Registration Form
 * @author Guillaume VanderEst <gui@exoduslabs.ca>
 * @package horadric
 */

class Horadric_UI_RegisterForm
{
    public $errors;

    public function __construct()
    {
        $this->errors = new ExoUI_Errors();
        $data = $this->get_data();
        if (empty($data->username)) { $this->errors->add('Username is required'); } 
        if (empty($data->email)) { $this->errors->add('Email address is required'); } 
        if (empty($data->password)) { $this->errors->add('Password is required'); } 
    }

    public function valid()
    {
        return count($this->errors) == 0;
    }

    public function submitted()
    {
        return $this->posted();
    }

    public function posted()
    {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }

    public function get_data()
    {
        $obj = new stdClass;
        $obj->username = @$_POST['username'];
        $obj->email = @$_POST['email'];
        $obj->password = @$_POST['password'];
        return $obj;
    }

    public function set_data($data)
    {
        $_POST['username'] = $data->username;
        $_POST['email'] = $data->email;
        $_POST['password'] = $data->password;
    }

    public function display()
    {
        $data = $this->get_data();
        return '
<form method="post">
    <div class="textbox"><label for="username">Username:</label><input type="text" id="username" name="username" value="' . $data->username . '" /></div>
    <div class="textbox"><label for="email">Email Address:</label><input type="text" id="email" name="email" value="' . $data->email . '" /></div>
    <div class="password"><label for="password">Password:</label><input type="password" id="password" name="password" value="" /></div>
    <div class="buttons"><input type="submit" value="Register" /></div>
</form>
        ';
    }
}
