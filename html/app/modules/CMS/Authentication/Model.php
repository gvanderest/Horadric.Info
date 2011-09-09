<?php
/**
 * CMS Authentication Model
 * @author Guillaume VanderEst <gui@exodusmedia.ca>
 * @package cms
 */

class CMS_Authentication_Model extends Exo_Model
{
    protected $accounts; // FIXME STUB REMOVE THIS WHEN YOU RELY ON DATABASE

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
        return FALSE;
    }
}
