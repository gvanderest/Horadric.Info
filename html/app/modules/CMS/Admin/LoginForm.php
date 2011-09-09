<?php
/**
 * CMS Admin Login Form
 * @author Guillaume VanderEst <gui@exodusmedia.ca>
 * @package cms
 */

class CMS_Admin_LoginForm extends ExoUI_Form
{
    /**
     * Instantiate the form
     * @param string $id (optional)
     * @return void
     */
    public function __construct($id = 'cms_admin')
    {
        $this->username = new ExoUI_Textbox('username');

        $this->password = new ExoUI_Password('password');

        $this->remember = new ExoUI_Checkbox('remember', array(
            'options' => array('Remember')
        ));

        $this->submit = new ExoUI_Submit('submit', array(
            'value' => 'Login'
        ));

        $this->add(array(
            $this->username,
            $this->password,
            $this->remember,
            $this->submit
        ));
    }
}
