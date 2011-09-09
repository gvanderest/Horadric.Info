<?php
/**
 * ExoSkeleton Session Container
 * @author Guillaume VanderEst <gui@exodusmedia.ca>
 * @package exo
 */

class Exo_Session
{
    protected $name;

    /**
     * Instantiate the session
     * @param string $name (optional) name of session to get a separate container
     * @return void
     */
    public function __construct($name = EXO_DEFAULT_SESSION_NAME)
    {
        $this->name = $name;

        // if the session has not yet been started, start it
        $session_id = session_id();
        if (empty($session_id))
        {
            session_start();
        }

        // and create the appropriate container
        if (!isset($_SESSION[$this->name]) || !is_array($_SESSION[$this->name]))
        {
            $_SESSION[$this->name] = array();
        }
    }

    /**
     * Get a session value
     * @param string $key
     * @return mixed data being held or NULL if non-existant
     */
    public function get($key)
    {
        if (!isset($_SESSION[$this->name][$key]))
        {
            return NULL;
        }
        return $_SESSION[$this->name][$key];
    }

    /**
     * Set a session value
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public function set($key, $value)
    {
        $_SESSION[$this->name][$key] = $value;
        return $_SESSION[$this->name][$key] === $value;
    }

    /**
     * Wipe a session value, or an entire sesssion
     * @param string $key (optional) if given, wipe the one value, otherwise, the entire session
     * @return bool
     */
    public function wipe($key = NULL)
    {
        // wipe entire session
        if ($key === NULL)
        {
            $_SESSION[$this->name] = array();
            return is_array($_SESSION[$this->name]) 
                && count($_SESSION[$this->name]) == 0;
        }

        // wipe a specific value
        unset($_SESSION[$this->name][$key]);
        return !isset($_SESSION[$this->name][$key]);
    }
}
