<?php
/**
 * Link and Web Helpers
 * @author Guillaume VanderEst <gui@exodusmedia.ca>
 */

if (!function_exists('link_email'))
{
    /**
     * Create a link to an email address
     * Defaults to output a simple 'mailto:' anchor, with the text of the address
     *
     * @param string $email
     * @param string $options (optional) if array, options, if string, the link text
     * @return string html for email anchor
     *
     * @example link_email('gui@exodusmedia.ca', array(
     *     'title' => 'title attribute',
     *     'subject' => 'email initial subject',
     *     'body' => 'email initial body'
     * ));
     * @see link_url for additional options
     */
    function link_email($email, $options = array())
    {
        // if options is not an array, it is the link text
        if (!is_array($options)) { $options = array('text' => $options); }

        $url = 'mailto:' . $email;
        if (empty($options['text'])) { $options['text'] = $email; }

        return link_url($url, $options);
    }
}

if (!function_exists('link_url'))
{
    /**
     * Create a anchor to a URL
     * Defaults to a simple anchor with the text of the address
     *
     * @example link_email('gui@exodusmedia.ca', array(
     *     'title' => 'title attribute',
     *     'text' => 'Anchor Text',
     *     'classes' => array('one', 'two'), // array of classes, or single
     *     'style' => 'css: here;', // can also be an array of arguments
     *     'return' => 'anchor' // returns an 'anchor' or just the 'url'
     * ));
     */
    function link_url($url, $options = array())
    {
        $defaults = array(
            'classes' => array(),
            'confirm' => '',
            'popup' => FALSE,
            'style' => '',
            'target' => '',
            'text' => $url,
            'title' => '',
            'return' => 'anchor'
        );
        $options = array_merge($defaults, $options);

        if (!is_array($options['classes'])) { $options['classes'] = array($options['classes']); }
        if ($options['popup']) { $options['target'] = '_blank'; }

        $tag_parts = array('href="' . $url . '"');

        if (count($options['classes']) > 0) { $tag_parts[] = 'class="' . htmlentities(implode(' ', $options['classes'])) . '"'; }
        if (!empty($options['confirm'])) { $tag_parts[] = 'onclick="return confirm(\'' . str_replace(array('"', "'"), "\\'", $options['confirm']) . '\');"'; }
        if (!empty($options['style'])) { $tag_parts[] = 'style="' . htmlentities($options['style']) . '"'; }
        if (!empty($options['target'])) { $tag_parts[] = 'target="' . htmlentities($options['target']) . '"'; }
        if (!empty($options['title'])) { $tag_parts[] = 'title="' . htmlentities($options['title']) . '"'; }
        if (empty($options['text'])) { $options['text'] = $url; }

        if ($options['return'] == 'url')
        {
            return $url;
        }
        return '<a ' . implode(' ', $tag_parts) . '>' . $options['text'] . '</a>';
    }
}

if (!function_exists('link_to'))
{
    /**
     * Link to a route, name is generic to keep things simple
     * @param mixed $route_id (string) the route array key, (array) the route, or (object) controller of a route application
     * @param array $args (optional) arguments to pass to the route
     * @param array $options (optional) options
     * @return string html
     * @see link_url() for options
     * @see exo/config/routes.php for route settings
     */
    function link_to($route_id, $args = array(), $options = array())
    {
        global $routes;

        // situations to make the text of the URL into a string provided as options
        if (is_string($options))
        {
            $options = array('text' => $options);

        // or args, with no options
        } elseif (is_string($args) && ($options === NULL || is_array($options) && count($options) == 0)) {

            $options = array('text' => $args);
            $args = array();

        } elseif ($args === NULL) {

            $args = array();
        }


        // the object was provided            
        if (is_object($route_id)) 
        {
            if (isset($route_id->route))
            {
                $route_id = $route_id->route;

            } elseif (isset($route_id->route_id)) {

                $route_id = $route_id->route_id;
            }
        }
    
        // route_id must be a string
        if (!is_string($route_id))
        {
            throw new Exo_Exception('An invalid route_id (' . gettype($route_id) . ') provided was invalid');
            return FALSE;
        }

        // otherwise, the $route_id was provided
        // fixme: remove this line?
        //$placeholders = Exo_Route::get_route_placeholders($route);
        $url = Exo_Route::get_route_url($route_id, $args);

        return link_url($url, $options);
    }
}

if (!function_exists('link_to_self'))
{
    /**
     * Link to the currently active route for a controller
     * @param array $args (optional)
     * @param array $options (optional)
     * @return string html
     * @see link_to for other arguments
     */
    function link_to_self($args = array(), $options = array())
    {

        // travel down the backtrace to find an object that can be routed to
        $backtrace = debug_backtrace(TRUE);
        for ($x = 0; $x < count($backtrace); $x++)
        {
            if (isset($backtrace[$x]['object']))
            {
                $object = $backtrace[$x]['object'];
                if (!isset($object->route_id))
                {
                    throw new Exo_Exception('The current controller does not have a route');
                    return FALSE;
                }

                return link_to($object->route_id, $args, $options);
            }
        }
        throw new Exo_Exception('Could not find a calling controller with a route');
        return FALSE;
    }
}

if (!function_exists('redirect_to'))
{
    /**
     * Provide a header redirect to a route
     * @param string $route
     * @param array $args
     * @param array $options (not yet used)
     * @return bool
     */
    function redirect_to($route, $args = array(), $options = array())
    {
        if (headers_sent())
        {
            return FALSE;
        }

        $url = link_to($route, $args, array('return' => 'url'));
        header("Location: " . $url);
        return TRUE;
    }
}

if (!function_exists('redirect_to_self'))
{
    /**
     * Redirect to the currently active route
     * This function must be called FROM an object with a $this->route_id or $this->route property
     * @param array $args
     * @param array $options (not yet used)
     * @return bool
     *
     */
    function redirect_to_self($args = array(), $options = array())
    {
        if (headers_sent())
        {
            return FALSE;
        }

        $url = link_to_self($args, array('return' => 'url'));
        header("Location: " . $url);
        return TRUE;
    }
}

if (!function_exists('request_segment_implode'))
{
    /**
     * Implode the segments of the URL that were requested
     * Only numeric args in order from index 0 will be imploded
     * @param array $args
     * @return string request
     */
    function request_segment_implode($args)
    {
        $output = '';
        $x = 0;
        while (isset($args[$x]))
        {
            $output .= EXO_REQUEST_SEGMENT_SEPARATOR . $args[$x];
            $x++;
        }
        return $output;
    }
}

if (!function_exists('curl_get'))
{
    /**
     * Get CURL content from somewhere quickly
     * @param string $url
     * @param array $gets (optional) GET request arguments
     * @return string output
     */
    function curl_get($url, $gets = array())
    {
        if (count($gets) > 0)
        {
            $url .= '?' . http_build_query($gets);
        }

        $ch = curl_init($url);
        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => TRUE
        ));
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}

if (!function_exists('curl_post'))
{
    /**
     * Get CURL content from somewhere using a post
     * @param string $url
     * @param array $args if $args2 is not given, this is POST data, if $args2 is given, this is GET data
     * @param array $args2 (optional) if provided, the first $args are GET, and these are POST
     * @return string output
     */
    function curl_post($url, $args, $args2 = array())
    {
        $post = $args;
        if (count($args2) > 0)
        {
            $post = $args2;
            $url .= '?' . http_build_query($args);
        }

        $ch = curl_init($url);
        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_POST => TRUE,
            CURLOPT_POSTFIELDS => $post
        ));
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}
