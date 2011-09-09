<?php
/**
 * CMS Model
 * @author Guillaume VanderEst <gui@exodusmedia.ca>
 * @package cms
 */

class CMS_Model extends Exo_Model
{
    /**
     * Load the ORM
     * @param void
     * @return void
     */
    public function __construct()
    {
        // figure out how this will work
        $this->orm = new ExoBase_ORM();
    }

    /**
     * Get a CMS page based on its URL
     * @param string $url
     * @return ExoBase_Record or FALSE on failure
     * @fixme stub
     */
    public function get_page_by_url($url)
    {
        $page = $this->orm->cms_pages(array(
            'where' => array('url', '=', $url),
            'amount' => 1
        ));
        return $page;
    }
}
