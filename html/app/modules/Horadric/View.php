<?php
/**
 * Horadric.Info Styling
 * @author Guillaume VanderEst <gui@exodusmedia.ca>
 */

class Horadric_View extends Exo_View
{
    public function __construct($controller = NULL)
    {
        parent::__construct($controller);
        $this->theme = 'horadric';
    }

    public static function display_item_slot($item)
    {
        switch ($item->slot)
        {
            case 'finger': return 'Ring';
        }
    }
    
    public static function display_article_content($content)
    {
        $content = preg_replace('/<h\:blizzard name="([a-z0-9]+?)" source="(.+?)">/sim', '<blockquote class="blizzard"><div class="quote-byline">Originally Posted by <strong>\\1</strong> (<a href="\\2" target="_blank">Source</a>)</div>', $content);
        $content = preg_replace('/<\/h:blizzard>/sim', '</blockquote>', $content);
        return $content;
    }

    public static function display_facebook_thread($url = NULL)
    {
        if (empty($url)) { $url = $_SERVER['SCRIPT_URI']; }
        return '<div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script><fb:comments href="' . $url . '" num_posts="2" width="958" colorscheme="dark"></fb:comments>';
    }
}
