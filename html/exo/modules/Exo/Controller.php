<?php
/**
 * ExoSkeleton Controller
 * @author Guillaume VanderEst <gui@exodusmedia.ca>
 * @package exo
 */

abstract class Exo_Controller
{
    public $load; // Exo_Loader
    public $data = array(); // any data for the controller
    public $route_id = NULL; // the route which is being followed

    /**
     * Instantiate the controller
     * @param string $route (optional) the route currently being followed to this controller
     * @return void
     */
    public function __construct($route_id = NULL)
    {
        $this->load = new Exo_Loader($this);
        $this->route_id = $route_id;
    }
}
