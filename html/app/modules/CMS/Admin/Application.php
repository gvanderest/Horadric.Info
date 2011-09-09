<?php
/**
 * CMS Administration Application
 * @author Guillaume VanderEst <gui@exodusmedia.ca>
 * @package cms
 */

class CMS_Admin_Application extends Exo_Controller
{
    // constants for certain calls to admin application
    const LOGIN_CONTROLLER = 'login'; // logging in
    const LOGOUT_CONTROLLER = 'logout'; // logging out
    const RETRIEVE_CONTROLLER = 'retrieve'; // retrieving password
    const DASHBOARD_CONTROLLER = 'dashboard'; // the dashboard, typical default

    // constant for the permission needed to access
    const LOGIN_NAME = 'cms_admin'; // the authenticator session container
    const LOGIN_PERMISSION = 'cms_admin'; // the name of the permission needed to access cms admin

    protected $auth;

    /**
     * Instantiate application and verify the user is authenticated
     * @param void
     * @return void
     */
    public function __construct($route_id)
    {
        parent::__construct($route_id);

        // instantiate authenticator
        $this->auth = new CMS_Authenticator(self::LOGIN_NAME);

        if ($this->auth->user_is_authenticated())
        {
            $user = $this->auth->get_user_account();
            $this->data['user'] = $user;
        }
    }

    /**
     * Load appropriate module
     * @param array $args
     * @return bool
     */
    public function _default($args)
    {
        $controller = isset($args[0]) ? $args[0] : NULL;

        // special actions that don't require login
        $special_actions = array(
            self::LOGIN_CONTROLLER,
            self::RETRIEVE_CONTROLLER,
            self::LOGOUT_CONTROLLER
        );

        // authenticate user
        // if the user is authenticated and has permission
        // get them to where they were going
        if ($this->auth->user_has_permission(self::LOGIN_PERMISSION)
            && !in_array($controller, $special_actions))
        {
            // no controller specified, redirect to default
            if (empty($controller))
            {
                return redirect_to_self(array(CMS_DEFAULT_ADMIN_CONTROLLER));
            }

            // special action, usually the default controller when logged in
            if ($controller == self::DASHBOARD_CONTROLLER)
            {
                return $this->dashboard($args);
            }
            
            // if the requested controller doesn't exist, display error 
            // FIXME stub, since there's no logic yet
            return $this->load->view('error');
        }

        // if a special action is being performed, perform it
        switch ($controller)
        {
        case self::LOGOUT_CONTROLLER:
            // can't log out if you're not logged in
            if (!$this->auth->user_has_permission(self::LOGIN_PERMISSION))
            {
                return redirect_to_self(array(self::LOGIN_CONTROLLER));
            }
            return $this->logout($args);
            break;
        case self::RETRIEVE_CONTROLLER:
            // if you're logged in, you don't need to retrieve your password
            if ($this->auth->user_has_permission(self::LOGIN_PERMISSION))
            {
                return redirect_to_self(array(CMS_DEFAULT_ADMIN_CONTROLLER));
            }
            return $this->retrieve($args);
        case self::LOGIN_CONTROLLER:
            // if you're logged in, you should be going to the default controller
            if ($this->auth->user_has_permission(self::LOGIN_PERMISSION))
            {
                return redirect_to_self(array(CMS_DEFAULT_ADMIN_CONTROLLER));
            }
            return $this->login($args);
            break;
        default:
            $redirect_args = array(self::LOGIN_CONTROLLER);
            if (count($args) > 0)
            {
                $redirect_args['r'] = link_to_self($args, array('return' => 'url'));
            }
            return redirect_to_self($redirect_args);
            break;
        }
    }

    /**
     * Dashboard (homepage for cms admin)
     * @param array $args
     * @return bool
     */
    public function dashboard($args)
    {
        // FIXME add code to display widgets for stats, blah blah
        return $this->load->view('dashboard');
    }

    /**
     * Log user out
     * @param array $args
     * @return bool
     */
    public function logout($args)
    {
        if (!$this->auth->user_logout())
        {
            throw new Exo_Exception('Could not log user out of CMS Admin');
            return FALSE;
        }

        // after logout go wherever the script requested, otherwise just back to login
        if (isset($args['r']))
        {
            return redirect($args['r']);
        }

        return redirect_to_self(array(self::LOGIN_CONTROLLER));
    }

    /**
     * Make user log in
     * @param array $args
     * @return bool
     * @fixme complete stub, rewrite with real logic
     */
    public function login($args)
    {
        $errors = array();

        /*$form = new CMS_Admin_LoginForm();

        // if the form is posted, attempt to log in
        if ($form->is_submitted() && $form->is_valid())
        {
            // check that login is valid
            $data = $form->get_data();
            $result = $this->auth->login($data->username, $data->password);
            if ($result)
            {
                if (isset($args['r']))
                {
                    return redirect($args['r']);
                } else {
                    return redirect_to_self($args);
                }
            } else {
                $form->add_error('The login information is not valid');
            }
        }

        $this->data['login_form'] = $form;
         */
        if ($_SERVER['REQUEST_METHOD'] == 'POST'
            && $this->auth->user_login(@$_POST['username'], @$_POST['password'])
        )
        {
            redirect_to_self();
        }

        $this->data['errors'] = $errors;

        return $this->load->view('login');
    }
}
