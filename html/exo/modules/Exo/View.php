<?php
/**
 * ExoSkeleton View
 * @author Guillaume VanderEst <gui@exodusmedia.ca>
 */

class Exo_View
{
    public $controller = NULL;
    public $route_id = NULL;

    public $theme = EXO_DEFAULT_THEME; 
    public $theme_path = NULL;
    public $theme_url = NULL;
    public $template_path;

    public function __construct($controller = NULL)
    {
        global $routes;

        $this->controller = $controller;
        if ($this->controller)
        {
            $this->route_id = $this->controller->route_id;

            if (isset($this->controller->route_id))
            {
                $route = $routes[$this->controller->route_id];
                if (isset($route['theme']))
                {
                    $this->theme = $route['theme'];
                }
            }
        }
    }

    /**
     * Load a view template
     * @param string $_template
     * @param array $_data (optional) associative array of data
     * @param array $_options (optional) none yet created
     * @return bool
     */
    public function load_template($_template, $_data = array(), $_options = array())
    {
        // if data is not provided, check if controller has any
        if ($_data === NULL && $this->controller !== NULL)
        {
            $_data = $this->controller->data;
        }

        // populate some helper variables for the view
        $this->theme_path = EXO_THEMES_PATH . '/' . $this->theme;
        $this->theme_url = EXO_THEMES_URL . '/' . $this->theme;
        $this->template_path = $this->theme_path . '/' . $_template . EXO_TEMPLATE_SUFFIX;
        if (!file_exists($this->template_path))
        {
            throw new Exo_Exception('The requested template "' . $this->theme . '/' . $_template . '" does not exist');
            return FALSE;
        }

        // populate all variables for the template to use
        foreach ($_data as $_key => $_value)
        {
            if (is_numeric($_key))
            {
                $_key = '_' . $_key;
            }
            $$_key = $_value;
        }

        // load template
        require($this->template_path);
        return TRUE;
    }
    public function render($template, $data = array(), $options = array()) { return $this->load_template($template, $data, $options); }
}
