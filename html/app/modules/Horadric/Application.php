<?php
/**
 * Horadric.Info Controller
 * @author Guillaume VanderEst <gui@exodusmedia.ca>
 * @package horadric
 */

class Horadric_Application extends Exo_Controller
{
    const SITEMAP_CACHE_AGE = 600; // 10 minutes

    public function __construct($route)
    {
        parent::__construct($route);
        $this->orm = new ExoBase_ORM();
        $this->theme_url = '/app/themes/horadric';
        $this->view = new Horadric_View($this);
    }

    /**
     * Titles Listing and How to Achieve Them
     */
    public function titles()
    {
        $titles_raw = array(
            array('source' => 'Reaching level 10', 'male' => 'Sir {name}', 'female' => 'Dame {name}'),
            array('source' => 'Reaching level 15', 'male' => 'Baron {name}', 'female' => 'Baroness {name}'),
            array('source' => 'Reaching level 20', 'male' => 'Count {name}', 'female' => 'Countess {name}'),
            array('source' => 'Reaching level 25', 'male' => 'Duke {name}', 'female' => 'Duchess {name}'),
            array('source' => 'Reaching level 30', 'male' => 'Lord {name}', 'female' => 'Lady {name}'),
            array('source' => 'Reaching level 40', 'male' => 'King {name}', 'female' => 'Queen {name}'),
            array('source' => 'Reaching level 50', 'male' => 'Defender {name}', 'female' => 'Defender {name}'),
            array('source' => 'Reaching level 60', 'male' => 'Champion {name}', 'female' => 'Champion {name}'),
            array('source' => 'Reaching level 70', 'male' => 'Guardian {name}', 'female' => 'Guardian {name}'),
            array('source' => 'Reaching level 80', 'male' => 'Conqueror {name}', 'female' => 'Conqueror {name}'),
            array('source' => 'Reaching level 90', 'male' => 'Destroyer {name}', 'female' => 'Destroyer { name}'),
            array('source' => 'Reaching level 100', 'male' => 'Avenger {name}', 'female' => 'Avenger {name}'),
            array('source' => 'Reach Max Level', 'male' => 'Patriarch {name}', 'female' => 'Matriarch {name}'),
            array('source' => 'Unknown', 'male' => 'Wanderer {name}'),
            array('source' => 'Unknown', 'male' => 'Local Hero {name}'),
            array('source' => 'Unknown', 'male' => 'Demon\'s Bane {name}'),
            array('source' => 'Unknown', 'male' => 'Tempted {name}'),
            array('source' => 'Unknown', 'male' => 'Fallen Hero {name}'),
            array('source' => 'Unknown', 'male' => 'Tainted {name}'),
            array('source' => 'Unknown', 'male' => 'Villain {name}'),
            array('source' => 'Unknown', 'male' => 'Rotten {name}'),
            array('source' => 'Unknown', 'male' => 'Scourge of Tristram {name}')
        );

        $titles = array();
        foreach ($titles_raw as $row)
        {
            $obj = new stdClass;
            foreach ($row as $key => $value)
            {
                $obj->$key = $value;
            }
            if (!isset($obj->female))
            {
                $obj->female = $obj->male;
            }
            $titles[] = $obj;
        }

        $this->data['titles'] = $titles;

        return $this->view->render('titles', $this->data);
    }

    /**
     * Scrape stuff
     */
    public function scrape($args)
    {
        $model = new Horadric_Scraper_Model();

        $source = $model->get_source(array('source_id' => 1));

        $scraper = new Horadric_Scraper_Blizz($source);
        $basics = $scraper->get_basic_scrapes();

        $basic_found = 0;
        $basic_inserted = 0;

        foreach ($basics as $basic)
        {
            $basic_found++;

            $existing = $model->get_scrape_by_hash($basic->hash);
            if (!$existing)
            {
                if (!isset($basic_inserted)) { $basic_inserted = 0; }
                $basic_inserted++;

                $model->add_scrape($basic);
            }
        }

        $complex_count = 0;
        $complex_updated = 0;

        $basics = $model->get_basic_scrapes();
        foreach ($basics as $basic)
        {
            $complex_found++;

            $complex = $scraper->get_complex_scrape($basic);
            if ($complex)
            {
                $complex_updated++;
                $model->update_scrape($unscrape->id, $complex);
            }
        }   
        
        print("<!DOCTYPE html>\n");
        print("<html>\n");
        print("<head>\n");
        print("<meta charset=\"utf-8\" />\n");
        print("</head>\n");
        print("<body>\n");
        echo "<pre>";
        printf("%d basic scrapes found\n", $basic_found);
        printf("%d basic scrapes inserted\n", $basic_inserted);
        printf("%d complex scrapes found\n", $complex_found);
        printf("%d complex scrapes updated\n", $complex_updated);
        echo "</pre>";
        print("</body>\n");
        print("</html>\n");
    }

    /**
     * Display a list of members or a specific member
     * @param array $args
     * @return bool
     */
    public function members($args)
    {
        if (isset($args[0]))
        {
            // try to get a specific member
            return $this->view->render('member');
        }

        return $this->view->render('members');
    }

    /**
     * Display guides index
     * @param array $args
     * @return bool
     */
    public function guides($args)
    {
        $guide_model = new Horadric_Guide_Model();

        // get a category to verify it exists
        if (isset($args[0]))
        {
            $category = $guide_model->get_guide_category(array('url' => $args[0]));
            if (!$category)
            {
                header("Status: 404 Category Not Found");
                return $this->view->render('guides/error');
            }

            $guides = $guide_model->get_guides(array('category_id' => $category->id));

            $data = array(
                'category' => $category,
                'guides' => $guides
            );

            return $this->view->render('guides/category', $data);
        }

        $categories = $guide_model->get_guide_categories();

        $data = array(
            'categories' => $categories
        );

        return $this->view->render('guides/index', $data);
    }

    /**
     * Log out of the site
     * @param array $args
     * @return bool
     */
    public function logout($args)
    {
        $auth_model = new CMS_Authenticator();
        
        // user isn't logged in
        if (!$auth_model->user_is_authenticated())
        {
            header("Location: /");
            exit();
        }

        // user is logged in, logging them out
        $auth_model->user_logout();
        $this->view->render('logout');
    }

    public function register_success()
    {
        return $this->view->render('register_success');
    }

    /**
     * Register for the site
     * @param array $args
     * @return bool
     */
    public function register($args)
    {
        $auth = new Horadric_Authenticator();

        // user is already logged in
        if ($auth->user_is_authenticated())
        {
            header("Location: /");
            exit();
        }

        // attempt a login
        $form = new Horadric_UI_RegisterForm();
        $data = $form->get_data();
        if ($form->posted() && $form->valid())
        {
            // email address needs to be an email address
            if (!preg_match('/^.+\@.+\..+$/i', $data->email))
            {
                $form->errors->add('Email address must be in the format user@domain.com');
            }

            // username must be 5-20 characters long, alphanumeric only
            if (!preg_match('/[a-z0-9]{5,20}/i', $data->username))
            {
                $form->errors->add('Username must be 5-20 characters in length and alphanumeric characters only');
            }

            // verify the username hasn't yet been used
            if ($auth->get_account_by_username($data->username))
            {
                $form->errors->add('The username "' . $data->username . '" has already been registered');
            }

            // verify the email address hasn't yet been used
            if ($auth->get_account_by_email($data->email))
            {
                $form->errors->add('The email address "' . $data->email . '" has already been registered');
            }

            if (count($form->errors) == 0)
            {

                if (!$auth->account_add($data))
                {
                    $form->errors->add('A database error has occurred while trying to regiser.  Please try again later.');
                } else {
                    header("Location: /register-success");
                    return TRUE;
                }
            }
        }

        $data = array( 'form' => $form );

        return $this->view->render('register', $data);
    }

    /**
     * Login to the site
     * @param array $args
     * @return bool
     */
    public function login($args)
    {
        $auth_model = new Horadric_Authenticator();

        // user is already logged in
        if ($auth_model->user_is_authenticated())
        {
            header("Location: /");
            exit();
        }

        // attempt a login
        $form = new Horadric_UI_LoginForm();
        if ($form->posted() && $form->valid())
        {
            $data = $form->get_data();
            if (!$auth_model->user_login($data->username, $data->password, $data->remember))
            {
                $form->errors->add('The username or password provided is invalid');
            } else {
                header("Location: /");
                exit();
            }
        }

        $data = array(
            'form' => $form
        );

        return $this->view->render('login', $data);
    }
    
    /**
     * Display a content page regarding crafting
     * @todo this will become a database of crafting recipes
     */
    public function crafting_index()
    {
        $this->load->view('crafting_index');
    }

    private function get_sitemap_url($url, $lastmod = NULL, $freq = 'monthly', $priority = 0.8)
    {
        if ($lastmod === NULL) { $lastmod = time(); }

        return '
   <url>
      <loc>' . $url . '</loc>
      <lastmod>' . date('Y-m-d', $lastmod) . '</lastmod>
      <changefreq>' . $freq . '</changefreq>
      <priority>' . $priority . '</priority>
   </url>
        ';
    }

    /**
     * Display a sitemap of the entire site
     */
    public function sitemap()
    {
        header("Content-type: application/xml");
        $cache_path = EXO_APP_CACHE . '/sitemap';
        if (!file_exists($cache_path) || (time() - filemtime($cache_path)) > self::SITEMAP_CACHE_AGE)
        {

$output = '';
$output .= '<?xml version="1.0" encoding="UTF-8"?' . '>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
';
            // all items
            $output .= $this->get_sitemap_url('http://horadric.info/item', NULL, 'daily');
            foreach ($this->orm->items as $item)
            {
                $output .= $this->get_sitemap_url('http://horadric.info/item/' . $item->id);
                $output .= $this->get_sitemap_url('http://horadric.info/item/' . $item->url);
            }

            // all cain stories
            $output .= $this->get_sitemap_url('http://horadric.info/cain', NULL, 'daily');
            foreach ($this->orm->blue_threads as $thread)
            {
                // TODO: make this listing have slugs
                $output .= $this->get_sitemap_url('http://horadric.info/cain/' . $thread->id);
            }

            // all classes
            $output .= $this->get_sitemap_url('http://horadric.info/class', NULL, 'daily');
            foreach ($this->orm->classes as $class)
            {
                $output .= $this->get_sitemap_url('http://horadric.info/class/' . $class->url);
            }

            // all npcs

            // all forum posts ?
            $output .= $this->get_sitemap_url('http://horadric.info/forums', NULL, 'hourly');

            // all guide categories
            $guide_model = new Horadric_Guide_Model();
            $output .= $this->get_sitemap_url('http://horadric.info/guides', NULL, 'daily');
            foreach ($guide_model->get_guide_categories() as $category)
            {
                $output .= $this->get_sitemap_url('http://horadric.info/guides/' . $category->url, NULL, 'daily');
            }
            foreach ($guide_model->get_guides() as $guide)
            {
                $output .= $this->get_sitemap_url('http://horadric.info/guide/' . $guide->url, NULL, 'daily');
            }

            // content page about crafting
            $output .= $this->get_sitemap_url('http://horadric.info/crafting', NULL, 'daily');

            $output .= '
</urlset> 
            ';
file_put_contents($cache_path, $output);
            
        }

        print(file_get_contents($cache_path));
        return TRUE;
    }

    private function blue_sort_by_date_asc($blue1, $blue2)
    {
        if ($blue1->date_posted == $blue2->date_posted) { return 0; }
        return $blue1->date_posted > $blue2->date_posted ? 1 : -1;
    }

    static function get_age_percentage($timestamp)
    {
        return min(1, abs(time() - $timestamp) / (48*60*60));
    }

    /**
     * List all Diablo 3 classes
     * @param array $args
     * @return bool
     */
    public function class_index($args)
    {
        $classes = $this->orm->classes;

        $this->data['classes'] = $classes;

        return $this->load->view('class_index');
    }

    /**
     * View a specific Diablo 3 class 
     * @param array $args
     * @return bool
     */
    public function class_view($args)
    {
        // invalid class given
        if (!isset($args['class_id']))
        {
            return $this->load->view('error');
        }

        // fetch requested class
        $class_id = $args['class_id'];
        if (is_numeric($class_id))
        {
            $class = $this->orm->classes($class_id);
        } else {
            $class = $this->orm->classes(array('where' => array(array('url', '=', $class_id))));
        }

        // invalid class given
        if (!$class)
        {
            return $this->load->view('error');
        }

        $data = array(
            'class' => $class,
            'skills' => $class->skills
        );

        return $this->load->view('class_view', $data);
    }

    public function cain_thread($args)
    {
        $thread_id = $args['thread_id'];
        $thread = $this->orm->blue_threads($thread_id);

        if (!$thread)
        {
            return $this->load->view('cain_invalid_thread');
        }

        $posts = $thread->posts;

        $this->data['thread'] = $thread;
        $this->data['posts'] = $posts;

        return $this->load->view('cain_view_thread');
    }

    public function cain($args)
    {
        $cain = new Horadric_Cain();
        $blues = $cain->get_diablo3_blue_posts();


        if (!is_array($blues))
        {
            // what to do if this process fails
            throw new Exception('CAIN failed at fetching blue posts');
            exit();
        }

        foreach ($blues as $blue)
        {
            $thread = $this->orm->blue_threads($blue->thread_id);
            if (!$thread)
            {
                $origin = $cain->get_diablo3_thread_original_post($blue->thread_id);

                $thread = $this->orm->blue_threads->_new();
                $thread->id = $blue->thread_id;
                $thread->title = $origin->title;
                $thread->date_posted = $origin->date_posted;
                $thread->_save();

                if ($origin->id !== $blue->id)
                {
                    $op = $this->orm->blue_posts->_new();
                    $op->id = $origin->id;
                    $op->title = $origin->title;
                    $op->author = $origin->author;
                    $op->avatar = $origin->avatar;
                    $op->blizzard_posted = ($origin->blizzard_posted) ? 1 : 0;
                    $op->date_posted = $origin->date_posted;
                    $op->content = $origin->content;
                    $op->url = $origin->url;
                    $op->_save();

                    $thread->posts[] = $op;
                }
            }
            
            if (!$thread)
            {
                // get the first post of a thread

                throw new Exception('The thread could not be created, cannot continue');
                return FALSE;
            }

            $post = $this->orm->blue_posts($blue->id);
            if (!$post)
            {
                $post = $this->orm->blue_posts->_new();

                $post->id = $blue->id;
                $post->title = $blue->title;
                $post->author = $blue->author;
                $post->avatar = $blue->avatar;
                $post->blizzard_posted = ($blue->blizzard_posted) ? 1 : 0;
                $post->date_posted = $blue->date_posted;
                $post->content = $blue->content;
                $post->url = $blue->url;
                $post->_save();

                $thread->posts[] = $post;
            }

            if (!$post)
            {
                throw new Exception('Post could not be created, cannot continue');
                return FALSE;
            }
        }

        $this->data['threads'] = $this->orm->blue_threads(array('sort' => array('date_posted', 'desc'), 'amount' => 40));
        $this->data['cache_age'] = $cain->get_diablo3_cache_date();

        return $this->load->view('cain_index');
    }

    public function items($args)
    {
        $raw_items = array(
            array('url' => 'flesh-tearer', 'type' => 'weapon', 'name' => 'Flesh Tearer', 'quality' => 'unique', 'weapon_type' => 'axe', 'hands' => 1),
            array('url' => 'etrayu', 'type' => 'weapon', 'name' => 'Etrayu', 'quality' => 'unique', 'weapon_type' => 'bow', 'hands' => 2),
            array('url' => 'demon-machine', 'type' => 'weapon', 'name' => 'Demon Machine', 'quality' => 'unique', 'weapon_type' => 'crossbow', 'hands' => 2),
            array('url' => 'manticore', 'type' => 'weapon', 'name' => 'Manticore', 'quality' => 'unique', 'weapon_type' => 'crossbow', 'hands' => 2),
            array('url' => 'starspine', 'type' => 'weapon', 'name' => 'Starspine', 'quality' => 'unique', 'weapon_type' => 'crossbow', 'hands' => 2),
            array('name' => 'Bakkan Caster', 'url' => 'bakkan-caster', 'type' => 'weapon', 'quality' => 'unique', 'weapon_type' => 'crossbow', 'hands' => 2),
            array('name' => 'Crossfire', 'url' => 'crossfire', 'type' => 'weapon', 'quality' => 'unique', 'weapon_type' => 'crossbow', 'hands' => 2),
            array('name' => 'Peacemaker', 'url' => 'peacemaker', 'type' => 'weapon', 'quality' => 'unique', 'weapon_type' => 'crossbow', 'hands' => 2),
            array('name' => 'The Riveter', 'url' => 'the-riveter', 'type' => 'weapon', 'quality' => 'unique', 'weapon_type' => 'crossbow', 'hands' => 2),
            array('name' => 'Shock Bolt Launcher', 'url' => 'shock-bolt-launcher', 'type' => 'weapon', 'quality' => 'unique', 'weapon_type' => 'crossbow', 'hands' => 2),
            array('name' => 'Echoing Fury', 'url' => 'echoing-fury', 'type' => 'weapon', 'quality' => 'unique', 'weapon_type' => 'mace', 'hands' => 1),
            array('name' => 'Odyn Son', 'url' => 'odyn-son', 'type' => 'weapon', 'quality' => 'unique', 'weapon_type' => 'mace', 'hands' => 1),
            array('name' => 'Neanderthal', 'url' => 'neanderthal', 'type' => 'weapon', 'quality' => 'unique', 'weapon_type' => 'mace', 'hands' => 1),
            array('name' => 'Devastator', 'url' => 'devastator', 'type' => 'weapon', 'quality' => 'unique', 'weapon_type' => 'mace', 'hands' => 1),
            array('name' => 'Nutcracker', 'url' => 'nutcracker', 'type' => 'weapon', 'quality' => 'unique', 'weapon_type' => 'mace', 'hands' => 1),
            array('name' => 'Dai Bachi', 'url' => 'dai-bachi', 'quality' => 'unique', 'weapon_type' => 'mace', 'hands' => 1, 'type' => 'weapon'),
            array('name' => 'Telranden\'s Hand', 'url' => 'telrandens-hand', 'quality' => 'unique', 'weapon_type' => 'mace', 'hands' => 1, 'type' => 'weapon'),
            array('name' => 'Nailbiter', 'url' => 'nailbiter', 'quality' => 'unique', 'weapon_type' => 'mace', 'hands' => 1, 'type' => 'weapon'),
            array('name' => 'Earthshatter', 'url' => 'earthshatter', 'quality' => 'unique', 'weapon_type' => 'mace', 'hands' => 1, 'type' => 'weapon'),
            array('name' => 'Wooden Leg', 'url' => 'wooden-leg', 'quality' => 'unique', 'weapon_type' => 'mace', 'hands' => 1, 'type' => 'weapon'),
            array('name' => 'The Incubuster', 'url' => 'the-incubuster', 'quality' => 'unique', 'weapon_type' => 'mace', 'hands' => 1, 'type' => 'weapon'),
            array('name' => 'Crushbane', 'url' => 'crushbane', 'quality' => 'unique', 'weapon_type' => 'mace', 'hands' => 1, 'type' => 'weapon'),
            array('name' => 'Sister Sledge', 'url' => 'sister-sledge', 'quality' => 'unique', 'weapon_type' => 'mace', 'hands' => 2, 'type' => 'weapon'),
            array('name' => 'Boneshatter', 'url' => 'boneshatter', 'quality' => 'unique', 'weapon_type' => 'mace', 'hands' => 2, 'type' => 'weapon'),
            array('name' => 'Groundpounder', 'url' => 'groundpounder', 'quality' => 'unique', 'weapon_type' => 'mace', 'hands' => 2, 'type' => 'weapon'),
            array('name' => 'Rockbreaker', 'url' => 'rockbreaker', 'quality' => 'unique', 'weapon_type' => 'mace', 'hands' => 2, 'type' => 'weapon'),
            array('name' => 'Cataclysm', 'url' => 'cataclysm', 'quality' => 'unique', 'weapon_type' => 'mace', 'hands' => 2, 'type' => 'weapon'),
            array('name' => 'Overfiend', 'url' => 'overfiend', 'quality' => 'unique', 'weapon_type' => 'mace', 'hands' => 2, 'type' => 'weapon'),
            array('url' => 'leather-hood', 'type' => 'armor', 'name' => 'Leather Hood', 'quality' => 'common', 'armor_type' => 'helm'),
            array('url' => 'skull-cap', 'type' => 'armor', 'name' => 'Skull Cap', 'quality' => 'common', 'armor_type' => 'helm'),
            array('url' => 'coif', 'type' => 'armor', 'name' => 'Coif', 'quality' => 'common', 'armor_type' => 'helm'),
            array('url' => 'scale-helmet', 'type' => 'armor', 'name' => 'Scale Helmet', 'quality' => 'common', 'armor_type' => 'helm'),
            array('url' => 'arming-cap', 'type' => 'armor', 'name' => 'Arming Cap', 'quality' => 'common', 'armor_type' => 'helm'),
            array('url' => 'plated-helm', 'type' => 'armor', 'name' => 'Plated Helm', 'quality' => 'common', 'armor_type' => 'helm'),
            array('url' => '', 'type' => 'armor', 'name' => 'Plated Helm', 'quality' => 'common', 'armor_type' => 'helm')
        );  

        $items = array();
        foreach ($raw_items as $row)
        {
            $obj = new stdClass;
            foreach ($row as $key => $value)
            {   
                $obj->$key = $value;
            }
            $items[] = $obj;
        }

        $this->data['items'] = $items;

        return $this->view->render('items', $this->data); 
    }

    public function item($args)
    {
        //header("Access-Control-Allow-Origin: *"); // add this to the JSON view
        return $this->load->view('construction'); 
    }

    public function news($args)
    {
        $model = new Forum_Model();
        $forum = $model->get_forum_by_url('horadric-news');
        $news = array_slice($model->get_forum_threads(array('forum_id' => $forum->id, 'order_by' => array('date_created', 'desc'))), 0, 10);

        $this->data['news'] = $news;

        return $this->load->view('home', $this->data);
    }

    public function home()
    {
        redirect_to('horadric_news');
    }
}
