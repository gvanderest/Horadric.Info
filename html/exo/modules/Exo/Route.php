<?php
/**
 * Route Methods
 * @author Guillaume VanderEst <gui@exodusmedia.ca>
 */

class Exo_Route
{
    /**
     * Get the pattern from a route
     * @param array $route
     * @return string $pattern or FALSE on no pattern
     * @static
     */
    public static function get_route_pattern($route)
    {
        if (!isset($route['pattern']) && isset($route[0]))
        {
            return $route[0];
        } elseif (isset($route['pattern'])) {
            return $route['pattern'];
        }
        return FALSE;
    }   

    /**
     * Get the placeholders from a route
     * @param array $route
     * @return array placeholders
     * @static
     */
    public static function get_route_placeholders($route)
    {
        $pattern = self::get_route_pattern($route);
        return self::get_route_pattern_placeholders($pattern);
    }

    /**
     * Parse out placeholders from a route pattern
     * @param string $pattern ex. ':subdomain.__DOMAIN__/
     * @return array placeholder names
     * @static
     */
    public static function get_route_pattern_placeholders($pattern)
    {
        $placeholders = array();

        // get placeholders, alphanumeric, with underscores with a letter starting it
        if (preg_match_all('/(\:[a-zA-Z][a-zA-Z0-9\_]*)/', $pattern, $matches))
        {
            foreach ($matches[1] as $match)
            {
                $placeholders[] = $match;
            }
        }
        return $placeholders;
    }

    /**
     * Get the placeholder values for a request
     * @param string $pattern route pattern
     * @param string $request (optional)
     */
    public static function get_route_arguments($pattern, $request = NULL)
    {
        if ($request === NULL)
        {
            $request = $_REQUEST[EXO_REQUEST_KEY];
        }

        $placeholders = self::get_route_pattern_placeholders($pattern);
    }

    /**
     * Get the request being made
     * @param void
     * @return string request
     * @statuc
     */
    public static function get_request()
    {
        $request = $_REQUEST[EXO_REQUEST_KEY];

        if (substr($request, 0, 1) != '/')
        {
            $request = $_SERVER['SERVER_NAME'] . '/' . $request;
        }

        // take off any trailing slash
        if (substr($request, -1, 1) == '/')
        {
            $request = substr($request, 0, -1);
        }

        return $request;
    }

    /**
     * Replace the symbols out of a route with escaped versions
     * @param array $route
     * @return string escaped
     * @static
     */
    public static function get_prepared_route_pattern($route)
    {
        $unescaped = self::get_route_pattern($route);
        if (!$unescaped)
        {
            return FALSE;
        }

        if (substr($unescaped, -1, 1) == '/')
        {
            $unescaped = substr($unescaped, 0, -1);
        }

        $pattern = '/^';

        // if the pattern starts with a slash, assume that it allows for any domain
        if (substr($unescaped, 0, 1) == '/' )
        {
            $pattern .= '.+?';
        } elseif (substr($unescaped, 0, 1) == '.') {

	    $pattern .= '.+?';
	    $unescaped = substr($unescaped, 1);
	}			

        // escape any symbols that don't belong to a placeholder
        $pattern .= preg_replace('/(' . EXO_NON_PLACEHOLDER_REGEXP . ')/', '\\\${1}', $unescaped);

        // segments are counted after the pattern
        if (isset($route['append_segments']) && $route['append_segments'])
        {
            $pattern .= '.*';
        }

        // replace out placeholders with 
        $pattern .= '$/';
        $pattern = preg_replace('/' . EXO_PLACEHOLDER_REGEXP . '/', '(.+)', $pattern);

        return $pattern;
    } 

    /**
     * Get route URL
     * @param array $route_id
     * @param array $args
     * @return string url
     * @static
     */
    public static function get_route_url($route_id, $args = array())
    {
        global $routes;

        // the route doesn't exist
        if (!isset($routes[$route_id]))
        {
            throw new Exo_Exception('The requested route "' . $route_id . '" does not exist');
            return FALSE;
        }

        $route = $routes[$route_id];

        // it is the default route, so its URL is just the subdomain plus numeric parts
        if ($route_id == EXO_DEFAULT_ROUTE 
            || isset($route['append_segments']) && $route['append_segments']
        )
        {
            $url = (isset($route['append_segments']) && $route['append_segments']) ? self::get_route_pattern($route) : '/';
            
            if (substr($url, -1) != '/')
            {
                $url .= '/';
            }

            // get numerical arguments out of the array, starting at 0
            $numerics = array();
            $x = 0;
            while (isset($args[$x]))
            {
                $numerics[] = $args[$x];
                unset($args[$x]);
                $x++;
            }

            // remove additional numeric arguments
            foreach ($args as $key => $value)
            {
                if (is_numeric($key))
                {
                    unset($args[$key]);
                }
            }
            
            $url .= implode(EXO_REQUEST_SEGMENT_SEPARATOR, $numerics);

        // otherwise, it's a normal route with placeholders
        } else {

            
            $url = self::get_route_pattern($route);
            foreach ($args as $key => $value)
            {
                if (is_numeric($key))
                {
                    unset($args[$key]);
                    continue;
                }
                $placeholder = ':' . $key;
                if (strpos($url, $placeholder) !== FALSE)
                {
                    $url = str_replace($placeholder, $value, $url);
                    unset($args[$key]);
                }
            }
        }

        // query string additional request variables
        if (count($args) > 0)
        {
            $url .= '?' . http_build_query($args, '_');
        }

        if (substr($url, 0, 1) != '/')
        {
            $url = 'http://' . $url;
        }

        // if exoskeleton is in a subfolder, prepend it
        return EXO_SUBFOLDER . $url;
    }

    /**
     * From a full request string, get the application request portion
     * @param string $full_request
     * @return string application request
     */
    public function get_request_portion($full_request)
    {
        // get the position of the slash
        $slash_pos = strpos($full_request, '/');

        // can not find request portion
        if ($slash_pos === FALSE)
        {
            return FALSE;
        }

        return substr($full_request, ($slash_pos + 1));
    }
}
