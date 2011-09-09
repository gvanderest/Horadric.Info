<?php
/**
 * D2 Runeword Application
 * @author Guillaume VanderEst <gui@exoduslabs.ca>
 * @package horadric
 */
class Horadric_D2_Runeword_Application
{
    /**
     * Instantiate the application
     * @param void
     * @return void
     */
    public function __construct()
    {
        $this->view = new Horadric_View($this);
        $this->model = new Horadric_D2_Runeword_Model();
    }

    /**
     * The homepage for searching
     * @param array $args
     * @return bool
     */
    public function index($args)
    {
        $options = array();
        $runewords = $this->model->get_runewords($options);

        $data['runewords'] = $runewords;

        $this->view->load_template('runewords', $data);
    }
}
