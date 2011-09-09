<?php
/**
 * Horadric Registration Form
 * @author Guillaume VanderEst <gui@exoduslabs.ca>
 * @package horadric
 */

class Forum_UI_ReplyForm
{
    public $errors;

    public function __construct()
    {
        $this->errors = new ExoUI_Errors();
        $data = $this->get_data();
        if (empty($data->body)) { $this->errors->add('Post body is required'); } 
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
        $obj->body = @$_POST['body'];
        return $obj;
    }

    public function set_data($data)
    {
        $_POST['body'] = $data->body;
    }

    public function display()
    {
        $data = $this->get_data();
        return '
<form method="post">
    <div class="textarea"><label for="reply-body">Reply Body:</label><textarea id="reply-body" name="body">' . $data->body . '</textarea></div>
    <div class="buttons"><input type="submit" value="Reply" /></div>
</form>
        ';
    }
}
