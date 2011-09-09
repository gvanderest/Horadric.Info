<?php
/**
 * Horadric Login Form
 * @author Guillaume VanderEst <gui@exoduslabs.ca>
 * @package horadric
 */

class Horadric_UI_LoginForm
{
    public $errors;

    public function __construct()
    {
        $this->errors = new ExoUI_Errors();
        $data = $this->get_data();
        if (empty($data->username)) { $this->errors->add('Username is required'); } 
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
        $obj->password = @$_POST['password'];
        $obj->remember = @$_POST['remember'];
        return $obj;
    }

    public function set_data($data)
    {
        $_POST['username'] = $data->username;
        $_POST['password'] = $data->password;
        $_POST['remember'] = $data->remember;
    }

    public function display()
    {
        $data = $this->get_data();
        return '
<form method="post">
    <div class="textbox"><label for="username">Username:</label><input type="text" id="username" name="username" value="' . $data->username . '" /></div>
    <div class="password"><label for="password">Password:</label><input type="password" id="password" name="password" value="" /></div>
    <div class="buttons"><input type="submit" value="Login" /></div>
    <div class="checkbox"><input type="checkbox" id="remember" value="1" name="remember" ' . ($data->remember ? 'checked="checked" ' : ''). '/> <label for="remember">Remember Login</label></div>
</form>
        ';
    }
}
