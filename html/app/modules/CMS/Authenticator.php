<?php
/**
 * CMS Authenticator
 * Authenticates users to accounts and verifies permissions
 * @author Guillaume VanderEst <gui@exodusmedia.ca>
 * @package cms
 */

class CMS_Authenticator extends Exo_Model
{
    protected $session;

    /**
     * Instantiate the authenticator
     * @param string $name (optional) the login name, if left blank, will make use of generic site-wide login
     * @return void
     */
    public function __construct($name = CMS_DEFAULT_LOGIN_NAME)
    {
        parent::__construct();
        $this->session = new Exo_Session($name);
        $this->cookie = new Exo_Cookie($name);

        $session_id = $this->session->get('user_id');
        $cookie_id = $this->cookie->get('user_id');
        if (empty($session_id) && !empty($cookie_id))
        {
            $this->session->set('user_id', $cookie_id);
        }

        // verify the user is STILL logged in
        // their account may have been removed or something
        if ($this->user_is_authenticated()
            && $this->get_account($this->get_user_id() === FALSE)
        )
        {
            $this->user_logout();
        }
    }

    /**
     * Get the currently logged in user's account
     * @param void
     * @return object or FALSE if not logged in
     */
    public function get_user_account()
    {
        if ($this->user_is_authenticated())
        {
            return $this->get_account($this->get_user_id());
        }
        return NULL;
    }

    /**
     * Attempt an authentication
     * On success, authenticates user
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function user_login($username, $password, $remember = TRUE)
    {
        $account_id = $this->account_login_is_valid($username, $password);
        if ($account_id !== FALSE)
        {
            $this->session->set('user_id', $account_id);
            if ($remember)
            {
                $this->cookie->set('user_id', $account_id, 60 * 60 * 24 * 7);
            }
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Remove the current authentication
     * Returns true if the user isn't logged in as well
     * @param void
     * @return bool
     */
    public function user_logout()
    {
        $this->session->wipe('user_id');
        $this->cookie->wipe('user_id');
        if ($this->session->get('user_id') === NULL)
        {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Get the user's account id
     * @param void
     * @return int id or NULL if not logged in
     */
    public function get_user_id()
    {
        return $this->session->get('user_id');
    }

    /**
     * Is the user authenticated?
     * @param void
     * @return bool
     */
    public function user_is_authenticated()
    {
        return $this->get_user_id() !== NULL;
    }

    /**
     * Is the requested login valid?
     * Currently just a pass-through
     * @param string $username
     * @param string $password plain-text
     * @return bool
     * @todo should this verify their permission too?
     */
    public function is_login_valid($username, $password)
    {
        return $this->is_login_valid($username, $password);
    }

    /**
     * Does the logged in user have a permission
     * @param string $permission the identifier key for a permission
     * @return bool
     * @see http://www.sympocm.com/docs/CMS_Authenticator/
     * @todo make that a real url one day
     */
    public function user_has_permission($permission)
    {
        return $this->user_is_authenticated()
            && $this->account_has_permission($this->get_user_id(), $permission);
    }

    /**
     * Create an account
     * @param object $data
     * @return int account id or FALSE on failure
     */
    public function account_add($data)
    {
        $sql = "
            INSERT INTO cms_accounts (date_created, date_updated, username, email, password) VALUES (:date, :date, :username, :email, :password)
        ";
        if ($this->db->query($sql, array(
            ':date' => date('Y-m-d H:i:s'),
            ':username' => $data->username,
            ':email' => $data->email,
            ':password' => md5($data->password)
        )))
        {
            return $this->db->get_insert_id();
        }
        return FALSE;
    }

    /**
     * Get an account
     * @param int $id
     * @return object or FALSE on failure
     */
    public function get_account($id)
    {
        $sql = "
            SELECT *
            FROM cms_accounts
            WHERE id = :id
        ";
        return $this->db->query_one($sql, array(':id' => $id));
    }

    /**
     * Get an account by its email address
     * @param string $email
     * @return object or FALSE on failure
     */
    public function get_account_by_email($email)
    {
        $sql = "
            SELECT *
            FROM cms_accounts
            WHERE email = :email
        ";
        return $this->db->query_one($sql, array(':email' => $email));
    }

    /**
     * Get an account by its username
     * @param string $username
     * @return object or FALSE on failure
     */
    public function get_account_by_username($username)
    {
        $sql = "
            SELECT *
            FROM cms_accounts
            WHERE username = :username
        ";
        return $this->db->query_one($sql, array(':username' => $username));
    }

    /**
     * Verify an account login is valid
     * @param string $username
     * @param string $password
     * @return int account_id if valid, FALSE if not
     */
    public function account_login_is_valid($username, $password)
    {
        $sql = "
            SELECT *
            FROM cms_accounts
            WHERE username = :username AND password = :password
        ";
        $account = $this->db->query_one($sql, array(':username' => $username, ':password' => md5($password)));
        if (!$account)
        {
            return FALSE;
        }
        return $account->id;
    }

    /**
     * Verify an account has a permission
     * @param int $account_id
     * @param string $permission code
     * @return bool
     */
    public function account_has_permission($account_id, $permission)
    {
        $account = $this->get_account($account_id);

        if (!$account)
        {
            return FALSE;
        }
        // FIXME: make this really use a table
        return ($permission == 'admin' && $account->username == 'Wrack');
    }
}
