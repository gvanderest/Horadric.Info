<?php
/**
 * ExoSkeleton Model
 * @author Guillaume VanderEst <gui@exodusmedia.ca>
 * @package exo
 */

class Exo_Model 
{
    public $db;

    /**
     * Instantiate the model
     * @param void
     * @return void
     */
    public function __construct($db = EXO_DEFAULT_DATABASE)
    {
        $this->db = new Exo_Database_Connection($db);
    }
}
