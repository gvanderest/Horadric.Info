<?php
/**
 * Forum Model
 * @author Guillaume VanderEst <gui@exoduslabs.ca>
 * @package forum
 */

class Forum_Model extends Exo_Model
{
    protected $auth;

    public function __construct()
    {
        parent::__construct();
        $this->auth = new CMS_Authenticator();
    }

    /**
     * Mark all threads in the forum as read
     * @param int $account_id
     * @param int $forum_id (optional) if specified, only marks all threads as read in that forum
     * @return bool
     */
    public function set_all_threads_viewed($account_id, $forum_id = NULL)
    {
        $sql = "
            DELETE FROM forum_thread_views
            WHERE account_id = :account_id
        ";
        $values = array(
            ':account_id' => $account_id
        );
        if (!$this->db->query($sql, $values))
        {
            return FALSE;
        }

        $sql = sprintf("
            INSERT INTO forum_thread_views (account_id, date_viewed, thread_id)
            SELECT %d account_id, '%s' date_viewed, id thread_id FROM forum_posts WHERE thread_id IS NULL
        ",
            $account_id,
            date('Y-m-d H:i:s')
        );
        $result = $this->db->query($sql);
        return $result;
    }

    /**
     * Get the latest viewed date for an account on a thread
     * @param int $account_id
     * @param int $thread_id
     * @param int $timestamp
     * @return int id of view record 
     */
    public function set_thread_viewed_date($account_id, $thread_id, $timestamp)
    {
        $sql = "
            DELETE FROM forum_thread_views
            WHERE account_id = :account_id
                AND thread_id = :thread_id
        ";
        $values = array(
            ':account_id' => $account_id, 
            ':thread_id' => $thread_id
        );
        $result = $this->db->query($sql, $values);
        if (!$result)
        {
            return FALSE;
        }

        $obj = new stdClass;
        $obj->account_id = $account_id;
        $obj->thread_id = $thread_id;
        $obj->date_viewed = $timestamp;

        return $this->db->insert('forum_thread_views', $obj);
    }

    /**
     * Get the latest viewed date for an account on a thread
     * @param int $account_id
     * @param int $thread_id
     * @return int unix timestamp
     */
    public function get_thread_viewed_date($account_id, $thread_id)
    {
        $sql = "
            SELECT date_viewed
            FROM forum_thread_views
            WHERE account_id = :account_id
                AND thread_id = :thread_id
        ";
        $values = array(
            ':account_id' => $account_id, 
            ':thread_id' => $thread_id
        );
        $result = $this->db->query_one($sql, $values);
        if ($result)
        {
            return $result->date_viewed;            
        }
        return 0;
    }

    /**
     * Create a user's vote for a post
     * @param int $account_id
     * @param int $post_id
     * @param int $vote -1, 1, 0
     * @return object response with success = 1 being good
     * @TODO move this to the CMS?
     */
    public function add_post_vote($account_id, $post_id, $vote)
    {
        $response = new stdClass;
        $response->success = 0;

        if (!in_array($vote, array(-1, 0, 1)))
        {
            return $response;
        }

        $values = array(
            ':entity_type' => 'forum_posts',
            ':entity_id' => $post_id,
            ':account_id' => $account_id
        );
        $sql = 'DELETE FROM votes WHERE entity_type = :entity_type AND entity_id = :entity_id AND account_id = :account_id';
        $result = $this->db->query($sql, $values);
        if (!$result)
        {
            return $response;
        }

        // if casting a vote, cast it
        if ($vote != 0)
        {
            $values['vote'] = $vote;

            $row = new stdClass;
            $row->entity_type = 'forum_posts';
            $row->entity_id = $post_id;
            $row->account_id = $account_id;
            $row->date_created = date('Y-m-d H:i:s');
            $row->score = $vote;

            $id = $this->db->insert('votes', $row);
            if (!$id)
            {
                return $response;
            }
        }

        // success!
        $response->success = 1;
        $response->voted = $vote;
        $response->entity_type = 'forum_posts';
        $response->entity_id = $post_id;
        $response->score = $this->get_post_score($post_id);
        return $response;
    }

    /**
     * Get a post's score
     * @param int $post_id
     * @return int
     */
    public function get_post_score($post_id)
    {
        $values = array(':entity_id' => $post_id);
        $sql = "
            SELECT SUM(v.score) score 
            FROM votes v 
            WHERE v.entity_type = 'forum_posts' 
                AND v.entity_id = :entity_id
        ";
        $result = $this->db->query_one($sql, $values);
        return (int)$result->score;
    }

    /**
     * Get a unique thread url
     * @param string $input
     * @return string unique url
     */
    public function get_unique_thread_url($input)
    {
        return $this->db->get_unique_url($input, 'forum_posts');
    }

    /**
     * Create a thread
     * @param object $data
     * @return int id or FALSE on failure
     */
    public function add_thread($data)
    {
        $sql = "
            INSERT INTO forum_posts (date_created, date_updated, author_id, title, url, body, forum_id, ip)
            VALUES (:date, :date, :author_id, :title, :url, :body, :forum_id, :ip)
        ";
        $values = array(
            ':date' => date('Y-m-d H:i:s'),
            ':author_id' => $data->author_id,
            ':title' => $data->title,
            ':url' => $data->url,
            ':body' => $data->body,
            ':forum_id' => $data->forum_id,
            ':ip' => $data->ip
        );
        if ($this->db->query($sql, $values))
        {
            return $this->db->get_insert_id();
        }
        return FALSE;
    }

    /**
     * Create a thread reply
     * @param object $data
     * @return int id or FALSE on failure
     */
    public function add_thread_reply($data)
    {
        $sql = "
            INSERT INTO forum_posts (date_created, date_updated, author_id, title, url, body, forum_id, thread_id, ip)
            VALUES (:date, :date, :author_id, :title, :url, :body, :forum_id, :thread_id, :ip)
        ";
        $values = array(
            ':date' => date('Y-m-d H:i:s'),
            ':author_id' => $data->author_id,
            ':title' => $data->title,
            ':url' => $data->url,
            ':body' => $data->body,
            ':forum_id' => $data->forum_id,
            ':thread_id' => $data->thread_id,
            ':ip' => $data->ip
        );
        if ($this->db->query($sql, $values))
        {
            return $this->db->get_insert_id();
        }
        return FALSE;
    }

    /**
     * Get the categories of the forum
     * @param void
     * @return array of objects
     */
    public function get_forum_categories()
    {
        $sql = "
            SELECT *
            FROM forum_categories
            ORDER BY rank, name
        ";
        return $this->db->query_all($sql);
    }

    /**
     * Get a forum by its url
     * @param string $url
     * @return object or NULL on failure
     */
    public function get_forum_by_url($url)
    {
        $sql = "
            SELECT *
            FROM forums
            WHERE url = :url
        ";
        return $this->db->query_one($sql, array(':url' => $url));
    }

    /**
     * Get all forum posts/threads, defaulting to the newest created ones
     * @param array $options (optional)
     * return mixed see above
     */
    public function get_forum_posts($options = array())
    {
        $defaults = array(
            'order_by' => NULL,
            'limit' => NULL
        );
        $options = array_merge($defaults, $options);

        $account = $this->auth->get_user_account();

        $values = array(
            ':score_window_date' => date('Y-m-d H:i:s', FORUM_SCORE_WINDOW_DATE),
            ':account_id' => ($account ? $account->id : NULL),
            ':entity_type' => 'forum_posts'
        );
        $sql = '
            SELECT p.*,
                a.id author_id,
                a.username author_name,
                a.avatar author_avatar,
                t.title thread_title,
                t.id thread_id,
                t.date_created thread_date_created,
                t.date_updated thread_date_updated,
                t.url thread_url,
                ta.id thread_author_id,
                ta.username thread_author_name,
                ta.avatar thread_author_avatar,
                s.score,
                ws.score window_score,
                v.score user_vote
                
            FROM forum_posts p
            LEFT JOIN cms_accounts a ON p.author_id = a.id
            LEFT JOIN forum_posts t ON IF(p.thread_id IS NULL, p.id, p.thread_id) = t.id
            LEFT JOIN cms_accounts ta ON t.author_id = ta.id

            LEFT JOIN votes v ON v.entity_type = :entity_type AND v.entity_id = p.id AND v.account_id = :account_id

            LEFT JOIN (
                SELECT entity_id, 
                    SUM(score) AS score
                FROM votes
                WHERE entity_type = \'forum_posts\'
                GROUP BY entity_id
            ) s ON p.id = s.entity_id

            LEFT JOIN (
                SELECT entity_id, 
                    SUM(score) AS score
                FROM votes
                WHERE entity_type = :entity_type
                    AND date_created >= :score_window_date
                GROUP BY entity_id
            ) ws ON p.id = ws.entity_id

            GROUP BY p.id
        ';
        if (!empty($options['order_by']))
        {
            $sql .= ' ORDER BY ' . implode(' ', $options['order_by']);
        } else { 
            $sql .= '
                ORDER BY p.date_created DESC
            ';
        }

        if (!empty($options['limit']))
        {
            $sql .= ' LIMIT 0, ' . $options['limit'] . ' ';
        }

        $results = $this->db->query_all($sql, $values);

        return $results;
    }

    /**
     * Get the threads in a forum
     * @param array $options (optional) array(
     *  'forum_id' => NULL, // if NULL/empty array, get all; if array of ints, get the ids; if integer, get one
     * )
     * return mixed see above
     */
    public function get_forum_threads($options = array())
    {
        $defaults = array(
            'forum_id' => NULL,
            'order_by' => NULL,
            'limit' => NULL
        );
        $options = array_merge($defaults, $options);

        $account = $this->auth->get_user_account();

        $values = array(
            ':score_window_date' => date('Y-m-d H:i:s', FORUM_SCORE_WINDOW_DATE),
            ':account_id' => ($account ? $account->id : NULL),
            ':entity_type' => 'forum_posts'
        );
        $sql = '
            SELECT t.*,
                lr.id latest_reply_id,
                lr.author_id latest_reply_author_id,
                lr.author_name latest_reply_author_name,
                lr.date_created latest_reply_date,
                lr.title latest_reply_title,
                a.id author_id,
                a.username author_name,
                a.avatar author_avatar,
                r.reply_count,
                s.score,
                ws.score window_score,
                v.score user_vote,
                ftv.date_viewed

            FROM forum_posts t
            LEFT JOIN (
                SELECT lr.id,
                    lr.author_id,
                    lr.title,
                    a.username author_name,
                    lr.date_created,
                    lr.thread_id
                FROM forum_posts lr
                LEFT JOIN cms_accounts a ON lr.author_id = a.id
                ORDER BY lr.date_created DESC
            ) lr ON t.id = lr.thread_id

            LEFT JOIN (
                SELECT COUNT(r.id) reply_count,
                    r.thread_id
                FROM forum_posts r
                GROUP BY r.thread_id
            ) r ON t.id = r.thread_id

            LEFT JOIN cms_accounts a ON t.author_id = a.id

            LEFT JOIN votes v ON v.entity_type = :entity_type AND v.entity_id = t.id AND v.account_id = :account_id

            LEFT JOIN forum_thread_views ftv ON ftv.thread_id = t.id AND ftv.account_id = :account_id

            LEFT JOIN (
                SELECT entity_id, 
                    SUM(score) AS score
                FROM votes
                WHERE entity_type = \'forum_posts\'
                GROUP BY entity_id
            ) s ON t.id = s.entity_id

            LEFT JOIN (
                SELECT entity_id, 
                    SUM(score) AS score
                FROM votes
                WHERE entity_type = :entity_type
                    AND date_created >= :score_window_date
                GROUP BY entity_id
            ) ws ON t.id = ws.entity_id

            WHERE t.thread_id IS NULL
        ';

        if (is_array($options['forum_id']) && count($options['forum_id'] > 0))
        {
            foreach ($options['forum_id'] as $index => $id) { $options['forum_id'][$index] = (int)$id; }
            $sql .= ' AND t.forum_id IN (' . implode(',', $options['forum_id']) . ') ';

        } elseif (is_numeric($options['forum_id'])) {

            $sql .= ' AND t.forum_id = :id ';
            $values[':id'] = $options['forum_id'];
        }

        $sql .= "
            GROUP BY t.id
        ";
        if (!empty($options['order_by']))
        {
            $sql .= ' ORDER BY ' . implode(' ', $options['order_by']);
        } else { 
            $sql .= '
                ORDER BY t.sticky DESC, IF(lr.date_created IS NULL, t.date_created, lr.date_created) DESC
            ';
        }

        if (!empty($options['limit']))
        {
            $sql .= ' LIMIT 0, ' . $options['limit'] . ' ';
        }

        $results = $this->db->query_all($sql, $values);

        foreach ($results as $index => $result)
        {
            $results[$index]->latest_reply_date = strtotime($result->latest_reply_date);
        }

        return $results;
    }

    /**
     * Get a thread by its url
     * @param string $url
     * @return object or FALSE on failure
     */
    public function get_thread_by_url($url)
    {
        $account = $this->auth->get_user_account();

        $sql = "
            SELECT t.*,
                a.username author_name,
                a.id author_id,
                a.title author_title,
                a.experience author_experience,
                a.guild author_guild,
                a.avatar author_avatar,
                a.class author_class,
                a.signature author_signature,
                s.score,
                ws.score window_score,
                v.score user_vote
            FROM forum_posts t 
            LEFT JOIN cms_accounts a ON t.author_id = a.id

            LEFT JOIN votes v ON v.entity_type = 'forum_posts' AND v.entity_id = t.id AND v.account_id = :account_id

            LEFT JOIN (
                SELECT entity_id, 
                    SUM(score) AS score
                FROM votes
                WHERE entity_type = 'forum_posts'
                GROUP BY entity_id
            ) s ON t.id = s.entity_id

            LEFT JOIN (
                SELECT entity_id, 
                    SUM(score) AS score
                FROM votes
                WHERE entity_type = 'forum_posts'
                    AND date_created >= :score_window
                GROUP BY entity_id
            ) ws ON t.id = ws.entity_id

            WHERE t.url = :url
        ";
        $result = $this->db->query_one($sql, array(
            ':url' => $url, 
            ':score_window' => FORUM_SCORE_WINDOW_DATE,
            ':account_id' => ($account ? $account->id : NULL)
        ));
        return $result;
    }

    /**
     * Get the replies to a thread
     * @param int $thread_id
     * @return array of objects
     */
    public function get_thread_replies($thread_id, $options = array())
    {
        $sql = "
            SELECT p.*,
                a.username author_name,
                a.id author_id,
                a.title author_title,
                a.experience author_experience,
                a.guild author_guild,
                a.avatar author_avatar,
                a.class author_class,
                a.signature author_signature,
                s.score,
                ws.score window_score,
                v.score user_vote

            FROM forum_posts p
            LEFT JOIN cms_accounts a ON p.author_id = a.id

            LEFT JOIN votes v ON v.entity_type = 'forum_posts' AND v.entity_id = p.id AND v.account_id = :account_id

            LEFT JOIN (
                SELECT entity_id, 
                    SUM(score) AS score
                FROM votes
                WHERE entity_type = 'forum_posts'
                GROUP BY entity_id
            ) s ON p.id = s.entity_id

            LEFT JOIN (
                SELECT entity_id, 
                    SUM(score) AS score
                FROM votes
                WHERE entity_type = 'forum_posts'
                    AND date_created >= :score_window
                GROUP BY entity_id
            ) ws ON p.id = ws.entity_id

            WHERE p.thread_id = :thread_id
            ORDER BY p.date_created ASC
        ";
        $results = $this->db->query_all($sql, array(
            ':thread_id' => $thread_id, 
            ':score_window' => date('Y-m-d H:i:s', FORUM_SCORE_WINDOW_DATE),
            ':account_id' => $this->auth->get_user_id()
        ));
        return $results;
    }

    /**
     * Increment the thread views by amount
     * @param int $thread_id
     * @param int $amount
     * @return bool
     */
    public function increment_thread_views($thread_id, $amount = 1)
    {
        $sql = "UPDATE forum_posts SET views = views + :amount WHERE id = :id";
        return $this->db->query($sql, array(':amount' => $amount, ':id' => $thread_id));
    }

    /**
     * Get a single forum, get_forums wrapper
     * @param int $forum_id
     * @return object or FALSE on failure
     */
    public function get_forum($forum_id) { return $this->get_forums(array('id' => $forum_id)); }

    /**
     * Get the forums (and their latest post)
     * @param array $options (optional) array(
     *  'id' => array(), // if NULL/empty array, get all; if array of ints, get those ids; if integer, get one
     * )
     * @return mixed see $ids argument
     */
    public function get_forums($options = array())
    {
        $defaults = array(
            'id' => array()
        );
        $options = array_merge($defaults, $options);

        $values = array();
        $sql = "
            SELECT f.*,
                lp.id latest_post_id, 
                lp.date_created latest_post_date,
                lp.author_id latest_post_author_id,
                lp.author_name latest_post_author_name,
                IF(lp.thread_id IS NULL, lp.url, lpt.url) latest_post_thread_url,
                lp.title latest_post_title,
                t.thread_count,
                r.reply_count
            FROM forums f

            LEFT JOIN (
                SELECT p.*, 
                    a.username author_name
                FROM forum_posts p
                LEFT JOIN cms_accounts a ON p.author_id = a.id
                ORDER BY date_created DESC
            ) lp ON f.id = lp.forum_id

            LEFT JOIN (
                SELECT p.*, 
                    a.username author_name
                FROM forum_posts p
                LEFT JOIN cms_accounts a ON p.author_id = a.id
                ORDER BY date_created DESC           
            ) lpt ON lp.thread_id = lpt.id

            LEFT JOIN (
                SELECT t.forum_id, COUNT(t.id) thread_count
                FROM forum_posts t
                WHERE t.thread_id IS NULL
                GROUP BY t.forum_id
            ) t ON f.id = t.forum_id

            LEFT JOIN (
                SELECT r.forum_id, COUNT(r.id) reply_count
                FROM forum_posts r
                WHERE r.thread_id IS NOT NULL
                GROUP BY r.forum_id
            ) r ON f.id = r.forum_id
        ";

        // what are they requesting
        if (is_array($options['id']) && count($options['id']) > 0)
        {
            foreach ($id as $index => $num) { $id[$index] = (int)$num; }
            $sql .= '
                WHERE f.id IN (' . implode(',', $id) . ')
            ';
        } elseif (is_numeric($options['id'])) {
            $sql .= ' WHERE f.id = :id ';
            $values[':id'] = $options['id'];
        }   

        $sql .= '
            GROUP BY f.id
            ORDER BY f.rank, f.name
        ';

        $forums = $this->db->query_all($sql, $values, array('date' => array('latest_post_date')));
        if (is_numeric($options['id']))
        {
            if (count($forums) > 0)
            {
                return $forums[0];
            }
            return NULL;
        } 
        return $forums;
    }

    /**
     * Check if the thread has been "viewed" yet by this session
     * @param int $id thread id
     * @retrn bool TRUE if viewed, FALSE 
     */
    public function is_thread_viewed($id)
    {
        if (!isset($_SESSION['thread_views']))
        {
            $_SESSION['thread_views'] = array();
        }
        return in_array($id, $_SESSION['thread_views']);
    }

    /**
     * Increment a thread count and store its viewedness
     * @param int $id thread id
     * @return bool
     */
    public function mark_thread_viewed($id)
    {
        if (!$this->increment_thread_views($id))
        {
            return FALSE;
        }
        if (!isset($_SESSION['thread_views']))
        {
            $_SESSION['thread_views'] = array();
        }

        array_push($_SESSION['thread_views'], $id);
        return $this->is_thread_viewed($id);
    }

}
