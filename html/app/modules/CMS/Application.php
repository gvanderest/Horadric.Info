<?php
/**
 * CMS Application
 * @author Guillaume VanderEst <gui@exodusmedia.ca>
 * @package cms
 */

class CMS_Application extends Exo_Controller
{
    /**
     * Instantiate the application
     * @param string $route_id likely 'default'
     * @return void
     */
    public function __construct($route_id)
    {
        parent::__construct($route_id);
        $this->model = new CMS_Model();
        //$this->load = new CMS_Loader();
    }

    /**
     * Load the requested CMS action
     * @param array $args
     * @return bool result
     */
     public function _default($args)
     {
         // no request was made, redirect to the default request
         if (!isset($args[0]))
         {
             return redirect_to_self(array(CMS_DEFAULT_PAGE));
         }

         // get the requested page
         $page = $this->model->get_page_by_url($args[0]);

         // FIXME: TEMPORARILY MAKE THE DATE_CREATED AND DATE_ACTIVATED 
         $page->date_activated = strtotime($page->date_activated);
         $page->date_deactivated = strtotime($page->date_deactivated);

         // the requested page does not exist or is not a valid object
         // or the requested page is not flagged active
         // or if given, the requested page is not yet activated date
         // or if given, the requested page is expired
         if (!$page 
             || !is_object($page)
             || !$page->active
             || $page->date_activated > 0 && $page->date_activated > time()
             || $page->date_deactivated > 0 && $page->date_deactivated <= time()
         )
         {
             return $this->display_error_page();
         }

         return $this->display_page($page);
     }

    /**
     * Display the error page
     * @return bool result
     * @todo may be moved to CMS_Loader
     */
    public function display_error_page()
    {
        header("Status: 404 Resource Not Found");
        $this->load->view(CMS_ERROR_TEMPLATE);
        return TRUE;
    }

    /**
     * Display a page
     * @param mixed $page a page object
     * @return bool result
     * @todo move this to CMS_Loader?
     */
    public function display_page($page)
    {
        $this->load->view($page->template, $page);
        return TRUE;
    }
}
