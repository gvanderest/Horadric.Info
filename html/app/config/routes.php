<?php
/**
 * Routes
 * @author Guillaume VanderEst <gui@exoduslabs.ca>
 * @package exo
 */

/**
 * ROUTES
 * @see http://www.exoduslabs.ca/framework/help/routes
 *
 * Routes are defined by Ruby-on-Rails-inspired placeholders, starting with a colon,
 * followed by alphanumeric characters, and underscores. Ex: ':id' or ':1test_3'
 * but not ':invalid.placeholder', as that will require the pattern to be 'something.placeholder',
 * with the 'something' being a wildcard.
 *
 * The 'controller' follows the following pattern: "Class_Name#method_name".  All request placeholders
 * will be passed to the method in an associative array (minus their colon (':') prefix)
 *
 * The first matching pattern will be the route followed, and all trailing pattern slashes will be
 * marked as optional to allow a non-failure for typos on a user's behalf.
 *
 * Note: If a placeholder is specified, it must be provided by the request; otherwise, a fall-back
 * route should be created.  For example: The pattern '/articles/:id' would require an ID to be given,
 * if it is not given, another route should be created for '/articles' to catch this request.
 *
 *
 * By default, there should be some example routes below...
 */
$routes['items'] = array('/items', 'controller' => 'Horadric_Application#items');
$routes['item'] = array('/item', 'append_segments' => TRUE, 'controller' => 'Horadric_Application#item');
$routes['titles'] = array('/titles', 'controller' => 'Horadric_Application#titles');
$routes['horadric_scrape'] = array('/scrape', 'append_segments' => TRUE, 'controller' => 'Horadric_Application#scrape');
$routes['horadric_forums'] = array('/forums', 'append_segments' => TRUE, 'controller' => 'Forum_Application#index', 'theme' => 'horadric');
$routes['horadric_members'] = array('/members', 'append_segments' => TRUE, 'controller' => 'Horadric_Application#members', 'theme' => 'horadric');
$routes['horadric_register'] = array('/register', 'controller' => 'Horadric_Application#register');
$routes['horadric_register_success'] = array('/register-success', 'controller' => 'Horadric_Application#register_success');
$routes['horadric_login'] = array('/login', 'controller' => 'Horadric_Application#login', 'theme' => 'horadric');
$routes['horadric_logout'] = array('/logout', 'controller' => 'Horadric_Application#logout');
$routes['horadric_sitemap'] = array('/sitemap', 'controller' => 'Horadric_Application#sitemap', 'theme' => 'horadric');
$routes['horadric_crafting'] = array('/crafting', 'controller' => 'Horadric_Application#crafting_index', 'theme' => 'horadric');
$routes['horadric_news'] = array('/news', 'controller' => 'Horadric_Application#news', 'theme' => 'horadric');
$routes['horadric_news_view'] = array('/news/view/:story_id', 'controller' => 'Horadric_Application#news_story', 'theme' => 'horadric');
$routes['horadric_classes'] = array('/classes', 'controller' => 'Horadric_Application#class_index', 'theme' => 'horadric');
$routes['horadric_class'] = array('/class/:class_id', 'controller' => 'Horadric_Application#class_view', 'theme' => 'horadric');
$routes['horadric2'] = array('/', 'controller' => 'Horadric_Application#home', 'theme' => 'horadric');
$routes['horadric'] = array('/', 'controller' => 'Horadric_Application#home', 'theme' => 'horadric');
$routes['horadric_sitemap'] = array('/sitemap', 'controller' => 'Horadric_Application#sitemap', 'theme' => 'horadric');
$routes['cain'] = array('/cain', 'controller' => 'Horadric_Application#cain', 'theme' => 'horadric');
$routes['cain_post'] = array('/cain/:thread_id', 'controller' => 'Horadric_Application#cain_thread', 'theme' => 'horadric');
$routes['d2runeword'] = array('/runeword', 'controller' => 'Horadric_D2_Runeword_Application#index', 'theme' => 'horadric');
$routes['horadric_guides'] = array('/guides/', 'append_segments' => TRUE, 'controller' => 'Horadric_Application#guides');
$routes['horadric_guide'] = array('/guide/', 'append_segments' => TRUE,' controller' => 'Horadric_Application#guide');
$routes['default'] = array('controller' => 'Horadric_Application#home'); // default

// TODO: Implement subdomain routing and __ARGS__ placeholder
// special placeholder: __DOMAIN__ allows matching based on domain (the name and suffix only) as well
//'special1' => array('pattern' => ':subdomain.__DOMAIN__/', 'controller' => 'Example_Application#special'), // gather the subdomain of the request
//'special2' => array('example.__DOMAIN__/', 'controller' => 'Example_Application#special'), // specify the subdomain
//'special3' => array(':subdomain.__DOMAIN__/:lang/:lang', 'controller' => 'Example_Application#special') // specify a lot
