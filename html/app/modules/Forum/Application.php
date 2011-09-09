<?php
/**
 * Forum Application
 * @author Guillaume VanderEst <gui@exoduslabs.ca>
 * @package forum
 */
class Forum_Application extends Exo_Controller
{
    const POSTS_PER_PAGE = 30;
    const THREADS_PER_PAGE = 50;

    public function __construct($route)
    {
        parent::__construct($route);
        $this->model = new Forum_Model();
        $this->view = new Forum_View($this);
        $this->auth = new Horadric_Authenticator();
    }

    /**
     * The controller that figures out where to go
     * @param array $args
     * @return bool
     */
    public function index($args)
    {
        if (!empty($args[0]))
        {
            switch ($args[0])
            {
            case 'read-all':
                return $this->read_all();
                break;
            case 'thread':
                if (!isset($args[1]))
                {
                    return $this->view->render('forum/error');
                }
                return $this->view_thread($args[1], $args);
                break;

            case 'vote-post-up':
            case 'vote-post-down':
            case 'vote-post-clear':
                if (!isset($args[1]))
                {
                    return $this->view->render('forum/error');
                }
                $score = ($args[0] == 'vote-post-up') ? 1 : (($args[0] == 'vote-post-down') ? -1 : 0);
                return $this->vote_post($args[1], $score, $args);
                break;
            case 'forum':
                if (!isset($args[1]))
                {
                    return $this->view->render('forum/error');
                }
                return $this->list_threads($args[1], $args);
                break;
            default:
                redirect_to_self();
            }
        }
        return $this->list_forums();
    }

    /**
     * Mark all threads as read
     */
    public function read_all()
    {
        $account = $this->auth->get_user_account();
        if ($account)
        {
            $this->model->set_all_threads_viewed($account->id);
        }
        redirect_to_self();
    }

    /**
     * Vote a post in a direction, 1, -1, 0
     */
    public function vote_post($post_id, $score, $args)
    {
        $account = $this->auth->get_user_account();

        // does the post exist?
        // TODO: verify

        $response = new stdClass;
        $response->success = 0;

        if (!$account)
        {
            $response->error = 'You must be logged in to vote';
            print(json_encode($response));
            return TRUE;
        }

        $response = $this->model->add_post_vote($account->id, $post_id, $score);
        print(json_encode($response));
        return TRUE;
    }

    /**
     * List the threads in a forum
     * @param string $forum_url
     * @param array $args all of the arguments in general
     * @return bool
     */
    public function list_threads($forum_url, $args)
    {
        $page = isset($args['p']) ? max(1, (int)$args['p']) : 1;
        $per_page = self::THREADS_PER_PAGE;

        $forum = $this->model->get_forum_by_url($forum_url);
        if (!$forum)
        {
            return $this->view->render('forum/error');
        }

        $threads = $this->model->get_forum_threads(array('forum_id' => $forum->id));

        $account = $this->auth->get_user_account();

        $form = new Forum_UI_ThreadForm();
        // they are attempting to post a reply
        if ($form->submitted() && count($form->errors) == 0)
        {
            // verify that they can post to this thread
            if (!$account && !FORUM_ALLOW_ANONYMOUS_THREADS)
            {
                $form->errors->add('You must be logged in to submit a thread');
            }

            // thread is locked!
            // todo: let admins and moderators through
            if ($forum->locked || $forum->thread_locked) {
            // forum is locked
                $form->errors->add('The forum is locked from creating threads');
            }

            if (count($form->errors) == 0)
            {
                $data = $form->get_data();

                // submit the reply
                $thread = new stdClass();
                $thread->ip = $_SERVER['REMOTE_ADDR'];
                $thread->author_id = $account->id;
                $thread->title = $data->title;
                $thread->url = $this->model->get_unique_thread_url($data->title);
                $thread->body = $data->body;
                $thread->forum_id = $forum->id;
                $thread->thread_id = $thread->id;

                $post_id = $this->model->add_thread($thread);

                if (!$post_id)
                {
                    $form->errors->add('A database error has occurred while posting your thread');
                } else {

                    // auto-vote-up for your own post
                    $vote_response = $this->model->add_post_vote($account->id, $post_id, 1);

                    // find out which page they should be on
                    $new_page = ceil((count($threads) + 1) / $per_page);

                    // redirect
                    redirect_to_self(array('thread', $thread->url, 'p' => $new_page));
                }
            }
        }

        $data = array(
            'forum' => $forum,
            'threads' => array_slice($threads, ($page - 1) * $per_page, $per_page),
            'page' => $page,
            'pages' => ceil(count($threads) / $per_page),
            'per_page' => $per_page,
            'account' => $account,
            'moderator' => FALSE,
            'admin' => $this->auth->user_has_permission('admin'),
            'thread_form' => $form
        );
        return $this->view->render('forum/threads', $data);
    }

    /**
     * List all of the forums
     * @param void
     * @return bool
     */
    public function list_forums()
    {
        $categories = $this->model->get_forum_categories();
        $forums = $this->model->get_forums();

        $data = array();
        $data['categories'] = $categories;
        $data['forums'] = $forums;

        $this->view->render('forum/index', $data);
    }

    /**
     * View a thread and its replies
     * @param string $thread_url
     * @param array $args
     * @return bool
     */
    public function view_thread($thread_url, $args)
    {
        $page = isset($args['p']) ? max(1, (int)$args['p']) : 1;
        $per_page = self::POSTS_PER_PAGE;

        $thread = $this->model->get_thread_by_url($thread_url);
        if (!$thread)
        {
            return $this->view->render('forum/error');
        }

        // increment the views
        if (!$this->model->is_thread_viewed($thread->id))
        {
            $this->model->mark_thread_viewed($thread->id);
        }

        $forum = $this->model->get_forum($thread->forum_id);
        $replies = $this->model->get_thread_replies($thread->id);

        $posts = array_merge(array($thread), $replies);

        $account = $this->auth->get_user_account();

        // they are attempting to post a reply
        $form = new Forum_UI_ReplyForm();
        if ($form->submitted() && count($form->errors) == 0)
        {
            // verify that they can post to this thread
            if (!$account && !FORUM_ALLOW_ANONYMOUS_REPLIES)
            {
                $form->errors->add('You must be logged in to submit a reply');
            }

            // thread is locked!
            // todo: let admins and moderators through
            if ($thread->locked)
            {
                $form->errors->add('The thread is locked');
            } elseif ($forum->locked) {
            // forum is locked
                $form->errors->add('The forum is locked');
            }

            if (count($form->errors) == 0)
            {
                $data = $form->get_data();

                // submit the reply
                $post = new stdClass();
                $post->ip = $_SERVER['REMOTE_ADDR'];
                $post->author_id = $account ? $account->id : NULL;
                $post->title = 'Re: ' . $thread->title;
                $post->body = $data->body;
                $post->forum_id = $forum->id;
                $post->thread_id = $thread->id;

                $post_id = $this->model->add_thread_reply($post);

                if (!$post_id)
                {
                    $form->errors->add('A database error has occurred while posting your reply');
                } else {

                    // auto-vote-up for your own post
                    $vote_response = $this->model->add_post_vote($account->id, $post_id, 1);

                    // find out which page they should be on
                    $new_page = ceil((count($posts) + 1) / $per_page);

                    // redirect
                    redirect_to_self(array('thread', $thread->url, 'p' => $new_page));
                }
            }
        }

        // compare the user's latest thread view date to the current posting
        $posts = array_slice($posts, ($page - 1) * $per_page, $per_page);
        $latest_viewed_date = $this->model->get_thread_viewed_date($account->id, $thread->id);
        if ($posts[count($posts)-1]->date_created > $latest_viewed_date)
        {
            $this->model->set_thread_viewed_date($account->id, $thread->id, $posts[count($posts)-1]->date_created);
        }

        $data = array(
            'thread' => $thread,
            'forum' => $forum,
            'posts' => $posts,
            'page' => $page,
            'pages' => ceil(count($posts) / $per_page),
            'per_page' => $per_page,
            'account' => $account,
            'moderator' => FALSE,
            'admin' => $this->auth->user_has_permission('admin'),
            'reply_form' => $form,
            'viewed_date' => $latest_viewed_date
        );
        return $this->view->render('forum/thread', $data);
    }
}
