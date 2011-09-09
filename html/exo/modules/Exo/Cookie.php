<?php
/**
 * ExoSkeleton Cookie Container
 * @author Guillaume VanderEst <gui@exodusmedia.ca>
 * @package exo
 */

class Exo_Cookie
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
    }

    /**
     * Get a cookie value
     * @param string $key
     * @return mixed data being held or NULL if non-existant
     */
    public function get($key)
    {
        if (!isset($_COOKIE[$this->name . '_' . $key]))
        {
            return NULL;
        }
        return $_COOKIE[$this->name . '_' . $key];
    }

    /**
     * Set a cookie value
     * @param string $key
     * @param mixed $value
     * @param int $time (optional) in seconds
     * @return bool
     */
    public function set($key, $value, $time = 0)
    {
        setcookie($this->name . '_' . $key, $value, time() + $time);
        $_COOKIE[$this->name . '_' . $key] = $value;
        return $_COOKIE[$this->name . '_' . $key] == $value;
    }

    /**
     * Wipe a session value, or an entire sesssion
     * @param string $key (optional) if given, wipe the one value, otherwise, the entire session
     * @return bool
     */
    public function wipe($key = NULL)
    {
        if (empty($key))
        {
            foreach (array_keys($_COOKIE) as $key)
            {
                $this->wipe($key);
            }
        }
        
        // wipe a specific value
        setcookie($this->name . '_' . $key, '', -1);
        unset($_COOKIE[$this->name . '_' . $key]);
        return !isset($_COOKIE[$this->name . '_' . $key]);
    }
}
