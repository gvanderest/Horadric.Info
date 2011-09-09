<?php
/**
 * Blue Tracker Library (Diablo 3) CAIN - Completely Automated Information Nabber
 * @author Guillaume VanderEst <gui@exodusmedia.ca>
 * @todo make use of caching
 */

class Horadric_Cain
{
    const DIABLO3 = 'd3';

    const CACHE_EXPIRY = 600; // 1 minutes

    //const D3_GENERAL_CACHE_PATH = EXO_PATH . '/temp/d3bluesraw.html';

    public $game;
    
    public function __construct($game = self::DIABLO3)
    {
        $this->game = $game;
    }

    public function get_diablo3_cache_date()
    {
        return @filemtime(EXO_PATH . '/temp/d3bluesraw.html');
    }

    /**
     * Get the basic thread list for blue threads (blues only), does not include stickies
     * @param int $amount (optional) how many blue threads' information to grab
     * @return array of objects with thread information
     */
    public function get_thread_list($amount = 5)
    {
        switch ($this->game)
        {
            case self::DIABLO3:
            default:
                return $this->get_diablo3_thread_list($amount);
        }
    }

    /**
     * Get the topic information and first post of a thread
     * @param int $thread_id
     * @return object data or FALSE on failure
     */
    public function get_diablo3_thread_original_post($thread_id)
    {
        $obj = new stdClass();
        $obj->thread_id = $thread_id;

        // get the post (the first one will do, because it's the only one that matters)
        $url = 'http://forums.battle.net/thread.html?topicId=' . $thread_id;
        $raw_html = curl_get($url);

        // could not load thread, danger will robinson! danger!
        if (!$raw_html)
        {
            return FALSE;
        }

        if (!preg_match('/<!-- Main Post Body -->(.+?)<!-- End div postshell -->/sm', $raw_html, $matches))
        {
            // couldn't find the thread/post
            return FALSE;
        }
        $raw_post = $matches[1];

        $obj->avatar = '';
        if (preg_match('/<td style="background: url\(\'(.+?)\'\) no-repeat 0 0; width: 64px; height: 64px;">/sm', $raw_post, $avatar_match))
        {
            $obj->avatar = $avatar_match[1];
        }

        $obj->author = 'Post Author';
        if (preg_match('/<div class="chardata">(.+?)<\/div>/sm', $raw_post, $author_match))
        {
            $obj->author = trim(strip_tags($author_match[1]));
        }

        $obj->id = 0;
        if (preg_match('/id = "id([0-9]+)_/sm', $raw_post, $post_id_match))
        {
            $obj->id = $post_id_match[1];
        }

        $obj->title = '';
        if (preg_match('/<b>0.&nbsp;(.+?)<\/b>/sm', $raw_post, $title_match))
        {
            $obj->title = $title_match[1];
        }

        $obj->date_posted = 0;
        if (preg_match('/[0-9]+\/[0-9]+\/[0-9]+ [0-9]+\:[0-9]+\:[0-9]+ (AM|PM) [a-z]+/is', $raw_post, $date_match))
        {
            $obj->date_posted = strtotime($date_match[0]);
        }

        $obj->content = '';
        if (preg_match('/<div class="rplol" id="rp_0">(.+?)<\/div>/sm', $raw_post, $message_match))
        {
            $raw_message = $message_match[1];
            $raw_message = str_replace('<br>', "\n", $raw_message);
            $obj->content = trim(strip_tags($raw_message, '<blockquote>'));
        }

        $obj->blizzard_posted = FALSE;
        if (preg_match('/Blizzard Poster/sm', $raw_post))
        {
            $obj->blizzard_posted = TRUE;
        }

        $obj->url = $url . '#0';

        return $obj;
    }

    public function get_diablo3_new_blue_posts()
    {
        $raw_url = 'http://us.battle.net/d3/en/forum/blizztracker/';
        $raw_html = curl_get($raw_url);

        //if (preg_match_all('//', 
    }


    /**
     * Get the diablo3 thread list
     * @return array of objects with thread information or FALSE on failure
     */
    public function get_diablo3_blue_posts()
    {
        $posts = array();

        $raw_url = 'http://forums.battle.net/search.html?stationId=3000&forumId=12007&searchText=&characterName=&blizzardPoster=true&recentPosts=72&pageNo={page}';
        // prepare all of the data
        $temp_path = EXO_PATH . '/temp/d3bluesraw.html';
        if (!file_exists($temp_path) || abs(time() - filemtime($temp_path)) > self::CACHE_EXPIRY)
        {
            @unlink($temp_path);

            // start the process of getting all the html
            $num_pages = 1; // default number of pages, this can change in the first iteration
            for ($page = 1; $page <= $num_pages; $page++)
            {
                $url = str_replace('{page}', $page, $raw_url);
                $raw_html = curl_get($url);
                file_put_contents($temp_path, $raw_html, ($page == 1) ? 0 : FILE_APPEND);

                // find the paginator to find out how many pages there are
                if ($page == 1)
                {
                    // if this fails, there is no paginator
                    if (preg_match('/<div id="paging">(.+?)<\/div>/sm', $raw_html, $paginator_match))
                    {
                        // find the highest pageNo=## possible, if this fails, there might only be one page?
                        if (preg_match_all('/pageNo=([0-9]+)/s', $paginator_match[1], $page_matches))
                        {
                            foreach ($page_matches[1] as $num)
                            {
                                $num_pages = max($num_pages, $num);
                            }
                        }
                    }
                    continue;
                }
            }
        }

        // oh no, cache file doesn't exist!
        if (!file_exists($temp_path))
        {
            return FALSE;
        }

        // now read the cache file, and go nuts
        $result = file_get_contents($temp_path); 

        preg_match_all('/<div id="floatingContainer(.+?)<!-- end resultbox -->/sm', $result, $matches);

        foreach ($matches[1] as $match)
        {
            // the post
            $post = new stdClass();

            // get employee avatar
            $post->avatar = '';
            if (preg_match("/background: url\('(.+?)'\);/sm", $match, $portrait_match)) // get portrait
            {
                $post->avatar = $portrait_match[1];
            }

            // thread-related parts
            $post->author = '';
            $post->thread_id = 0;
            $post->id = 0;
            $post->title = 'Untitled';
            $post->date_posted = time();
            if (preg_match('/<li class = "userdata">(.+?)<\/li>/sm', $match, $user_data_match))
            {
                $user_data = $user_data_match[1];
                $tagless_user_data = strip_tags($user_data);

                // get their name
                if (preg_match('/by ([a-z0-9]+)/is', $tagless_user_data, $post_author_match))
                {
                    $post->author = $post_author_match[1];
                }

                // get the topic of the thread
                if (preg_match('/<a href = "thread.html?.+?">(.+?)<\/a>/sm', $user_data, $post_title_match))
                {
                    $post->title = $post_title_match[1];
                }

                // get the thread_id
                if (preg_match('/topicId=([0-9]+)/', $user_data, $thread_id_match))
                {
                    $post->thread_id = $thread_id_match[1];
                }

                // get the post_id
                if (preg_match('/postId=([0-9]+)/sim', $user_data, $post_id_match))
                {
                    $post->id = $post_id_match[1];
                }
                
                // get the date
                if (preg_match('/[0-9]+\/[0-9]+\/[0-9]+ [0-9]+\:[0-9]+\:[0-9]+ (AM|PM) [a-z]+/is', $user_data, $date_match))
                {
                    $post->date_posted = strtotime($date_match[0]);
                }
            }
            
            // post message
            $post->content = '';
            if (preg_match("/<div class=\"message-format\">(.+?)<\/div>/sm", $match, $message_match))
            {
                $raw_message = $message_match[1];
                $raw_message = str_replace('<br>', "\n", $raw_message);
                $raw_message = str_replace('<blockquote><small><hr NOSHADE color = "#9E9E9E" size = "1"><small class = "white">Q u o t e:</small><br>', '<blockquote>', $raw_message);
                $raw_message = str_replace('<hr NOSHADE color = "#9E9E9E" size = "1"></small></blockquote>' . "\r" . '<br>' . "\r" . '<br>', '</blockquote>', $raw_message);
                $post->content = trim(strip_tags($raw_message, '<blockquote><ul><li><ol>'));
            }

            // post url
            $post->url = '';
            if (preg_match('/<a href = "(thread\.html(;.+?)\?topicId=(.+?))" class="btn"><span>Jump to Post/', $match, $url_match))
            {
                $post->url = 'http://forums.battle.net/thread.html?topicId=' . $url_match[3];
            }

            // did a blizzard employee post it?
            $post->blizzard_posted = FALSE;
            if (preg_match('/<img src="\/images\/blizz.gif" alt="Blizzard Entertainment" \/>/sm', $match)
                || preg_match('/style="background\: url\(\'\/images\/portraits\/bliz\/bliz.gif\'\)\;/sm', $match))
            {
                $post->blizzard_posted = TRUE;
            }

            $posts[] = $post;
        }

        return $posts;
    }
}
