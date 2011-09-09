<?php
/**
 * ExoSkeleton Loader
 * Loads configuration files, environment settings, views, etc.
 * @author Guillaume VanderEst <gui@exodusmedia.ca>
 */

class Exo_Loader
{
    protected $routes;
    protected $environments;

    protected $controller; // the controller referencing the loader

    /**
     * Instantiate the loader
     * @param void
     * @return void
     */
    public function __construct($controller = NULL)
    {
        // do not allow versions of PHP lower than 5.2, because they haven't been tested
        $version = explode('.', PHP_VERSION);
        if ($version[0] < 5 || $version[0] == 5 && $version[1] < 2)
        {
            throw new Exo_Exception('PHP version must be 5.2+');
            return;
        }

        // if a controller is using this loader, provide a reference
        $this->controller = $controller;

        global $routes;
        $this->routes = $routes;

        global $environments;
        $this->environments = $environments;
    }

    /**
     * Load any environment-specific settings
     * @see exo/config/environments.php
     * @param void
     * @return bool
     */
    public function environment()
    {
        return TRUE;
    }


    /**
     * Load a helper library
     * @param string $helper
     * @return bool
     */
    public function helper($helper)
    {
        $paths = array(
            EXO_APP_HELPERS_PATH . '/' . $helper . '.php',
            EXO_HELPERS_PATH . '/' . $helper . '.php'
        );
        foreach ($paths as $path)
        {
            if (file_exists($path))
            {
                require_once($path);
                return TRUE;
            }
        }
        throw new Exo_Exception('Helper "' . $helper . '" could not be loaded');
        return FALSE;
    }

    /**
     * Load helpers
     * @param array $names (optional) helper names
     * @return bool
     * @see exo/config/helpers.php
     * @todo allow $names to work
     */
    public function helpers($names = NULL)
    {
        global $helpers;
        if ($names === NULL)
        {
            $names = $helpers;
        }

        if (!isset($helpers))
        {
            $helpers = array();
        }

        if (!is_array($helpers))
        {
            $helpers = array($helpers);
        }

        foreach ($helpers as $helper)
        {
            if (!$this->helper($helper))
            {
                return FALSE;
            }
        }
        return TRUE;
    }

    /**
     * Load the requested route
     * @see exo/config/routes.php
     * @param string $route_name (optional) force a certain route to load
     * @param array $route_args (optional) arguments to pass to the forced route
     * @return bool
     */
    public function route($route_name = NULL, $route_args = array())
    {
        global $routes;

        // TODO: support forced routing
        if ($route_name !== NULL)
        {
            throw new Exception('Forced routing not yet supported');
            return FALSE;
        }

        // if no 'pattern' is defined, but an index 0 is.. THAT is the pattern
        foreach ($routes as $route_name => $route)
        {
            if (isset($route[0]) && !isset($route['pattern']))
            {
                $route['pattern'] = $route[0];
            }
        }

        if (!isset($routes) || !is_array($routes))
        {
            throw new Exo_Exception('No routes found');
            return FALSE;
        }

        // pattern match for the requested route
        foreach ($routes as $route_id => $route)
        {
            // if a first key is given and no 'pattern', use that as the pattern
            if (isset($route[0]) && !isset($route['pattern']))
            {
                $route['pattern'] = $route[0];
            }

            $request = Exo_Route::get_request();
            $route_controller = $route['controller'];
            $raw_pattern = Exo_Route::get_route_pattern($route);

            // default route always succeeds, but is always last
            $args = array();
            if ($route_id != EXO_DEFAULT_ROUTE)
            {
                $placeholders = Exo_Route::get_route_placeholders($route);
                $pattern = Exo_Route::get_prepared_route_pattern($route);

                // attempt match the request against the pattern
                if (!preg_match($pattern, $request, $matches))
                {
                    continue;
                }

                // populate the appropriate arguments
                $count = 0;
                foreach ($placeholders as $index => $placeholder)
                {
                    $count++;
                    $args[substr($placeholder, 1)] = $matches[$count];
                }

                // replace placeholders with arguments out of the controller string
                foreach ($args as $key => $value)
                {
                    $route_controller = str_replace(':' . $key, $value, $route_controller);
                }
            }
            
            // populate request segments into argument list
            $request_portion = Exo_Route::get_request_portion($request);
            if (!empty($request_portion))
            {
                $segments = explode(EXO_REQUEST_SEGMENT_SEPARATOR, $request_portion);
                
                // if the segments are appended to the route, pop those first few off
                if (isset($route['append_segments']) && $route['append_segments'])
                {
                    $pattern_segments = $raw_pattern;
                    // if it starts with a slash, take it off
                    if (substr($pattern_segments, 0, 1) == '/') { $pattern_segments = substr($pattern_segments, 1); }
                    // if it ends with a slash, take it off
                    if (substr($pattern_segments, -1, 1) == '/') { $pattern_segments = substr($pattern_segments, 0, -1); }

                    for ($x = 0; $x < count($pattern_segments) && count($segments) > 0; $x++)
                    {
                        array_shift($segments);
                    }
                }
               
                $args = array_merge($args, $segments);
                // @todo make this clean
                $args = array_merge($_GET, $args);
                unset($args[EXO_REQUEST_KEY]);
            }

            // figure out which controller and method to load
            $controller_parts = explode('#', $route_controller);
            $controller = $controller_parts[0];
            $method = (isset($controller_parts[1])) ? $controller_parts[1] : EXO_DEFAULT_CONTROLLER_METHOD;

            try
            {
                $exists = class_exists($controller);
                $load = new $controller($route_id);

            } catch (Exception $exception) {
                
                throw $exception;
                return FALSE;
            }

            // check the method is allowed to be accessed
            $reflection = new ReflectionMethod($controller, $method);
            if (!$reflection->isPublic())
            {
                throw new Exo_Exception('The requested controller method "' . $method . '" could not be loaded');
                return FALSE;
            }

            $load->$method($args);
            return TRUE;
        }
        throw new Exo_Exception('A valid route was not found');
        return FALSE;
    }

    /**
     * Load a view
     * @param string $template
     * @param array $data (optional) if Loader has a controller, data is defaulted from controller
     * @param array $options (optional) none yet
     * @return bool
     */
    public function view($template, $data = NULL, $options = array())
    {
        $view = new Exo_View($this->controller);
        return $view->load_template($template, $data, $options);
    }
}
