<?php
/**
 * New Thread Form
 * @author Guillaume VanderEst <gui@exoduslabs.ca>
 * @package forum
 */

class Forum_UI_ThreadForm
{
    public $errors;

    public function __construct()
    {
        $this->errors = new ExoUI_Errors();
        $data = $this->get_data();
        if (empty($data->title)) { $this->errors->add('Thread title is required'); } 
        if (empty($data->body)) { $this->errors->add('Thread body is required'); } 
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
        $obj->title = @$_POST['title'];
        $obj->body = @$_POST['body'];
        return $obj;
    }

    public function set_data($data)
    {
        $_POST['title'] = $data->title;
        $_POST['body'] = $data->body;
    }

    public function display()
    {
        $data = $this->get_data();
        return '
<form method="post">
    <div class="textbox"><label for="thread-title">Title:</label><input type="text" id="thread-title" name="title" value="' . $data->body . '" /></div>
    <div class="textarea"><label for="thread-body">Post Body:</label><textarea id="thread-body" name="body">' . $data->body . '</textarea></div>
    <div class="buttons"><input type="submit" value="Post Thread" /></div>
</form>
        ';
    }
}
