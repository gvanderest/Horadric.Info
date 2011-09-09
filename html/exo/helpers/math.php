<?php
/**
 * Math Helpers
 * @author Guillaume VanderEst <gui@exodusmedia.ca>
 * @package exoskeleton
 */

if (!function_exists('gps_distance'))
{
    /**
     * Get the distance between two lat/lon points
     * @param float $lat1
     * @param float $lon1
     * @param float $lat2
     * @param float $lon2
     * @param string $unit (optional) accepts 'km' (default) and 'mi'
     * @return float distance in 
     */
    function gps_distance($lat1, $lon1, $lat2, $lon2, $unit = 'km')
    {
        //$earth_radius = 3960.00;
        $mi_in_km = 1.609344;

        $delta_lat = abs($lat2 - $lat1);
        $delta_lon = abs($lon2 - $lon1);

        $distance  = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($delta_lon)) ;
        $distance  = acos($distance);
        $distance  = rad2deg($distance);
        $distance  = $distance * 60 * 1.1515;
        $distance  = round($distance, 4);

        switch (strtolower($unit))
        {
        case 'mi': 
            return $distance;
            break;
        case 'km': 
        default: 
            return $distance * $mi_in_km;
            break;
        }

        // should never get here
        return 0;
    }
}
