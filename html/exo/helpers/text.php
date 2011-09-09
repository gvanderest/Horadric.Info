<?php
/**
 * Text and String Helper Functions
 * @author Guillaume VanderEst <gui@exodusmedia.ca>
 */

if (!function_exists('s'))
{
    /**
     * Function to return singular or plural version of a word, depending on quantity
     * @param int $amount
     * @param string $singular version of word for singular
     * @param string $plural (optional) version of word for plural, defaults to appending s to singular
     * @return string appropriate word for quantity
     */
    function s($amount, $singular, $plural = NULL)
    {
        // default the plural to appending 's'
        if ($plural === NULL)
        {
            $plural = $singular . 's';
        }

        // only in the case of 1 or -1 should it return the singular
        return abs($amount) == 1 ? $singular : $plural;
    }

}

if (!function_exists('truncate'))
{
    /**
     * Truncate a string if it goes beyond a certain length
     * @param string $input
     * @param int $length
     * @param string $symbol (optional) identifier of truncation, defaults to '...'
     */
    function truncate($input, $length, $symbol = '...')
    {
        if (strlen($input) > $length)
        {
            return substr($input, 0, ($length - strlen($symbol))) . $symbol;
        }
        return $input;
    }
}

if (!function_exists('urlify'))
{
    /**
     * Convert a string to be an SEO-friendly URL segment
     * @param string $input
     * @param array $options (optional) not yet used, but will for uniqueness against databases/ORM
     * @return string output
     */
    function urlify($input, $options = array())
    {
        $output = '';
        
        // allow only alphanumerics
        for ($x = 0; $x < strlen($input); $x++)
        {
            $char = strtolower(substr($input, $x, 1));
            $output .= (preg_match('/^[a-z0-9]$/', $char)) ? $char : '-';
        }

        // multiple dashes collapase to one
        $output = preg_replace('/\-+/', '-', $output);
        $output = trim($output, ' -');

        return $output;
    }
}
