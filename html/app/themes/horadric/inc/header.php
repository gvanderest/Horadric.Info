<?php
/**
 * Horadric.Info Header
 * @author Guillaume VanderEst <gui@exodusmedia.ca>
 */

$_forum_view = new Forum_View();

$orm = new ExoBase_ORM();
$tmp_blues = $orm->blue_threads(array('sort' => array('date_posted', 'desc'), 'amount' => 5));

if (isset($_title)) { $title = $_title; }
if (isset($_description)) { $description = $_description; }

if (!isset($title)) { $title = 'Horadric.Info'; }
if (!isset($description)) { $description = 'Diablo 3 Item, NPC and Quest Database'; }
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"
      xmlns:og="http://ogp.me/ns#"
      xmlns:fb="http://www.facebook.com/2008/fbml">
    <head>
        <title><?= $title ?> | Horadric.Info</title>
        <meta charset="utf-8" />
        <meta property="description" content="<?= htmlentities($description) ?>" />

        <meta property="fb:admins" content="612695566" />
        <meta property="fb:app_id" content="103816563052294" />
        <meta property="og:image" content="http://horadric.info/app/themes/horadric/img/cube.png" />
        <meta property="og:title" content="<?= htmlentities($title) ?>" />
        <meta property="og:description" content="<?= htmlentities($description) ?>" />
        <meta property="og:url" content="<?= $_SERVER['SCRIPT_URI']; ?>" />
        <meta property="og:site_name" content="Horadric.Info" />
        <meta property="og:type" content="article" />

        <link rel="stylesheet" type="text/css" href="<?= $this->theme_url ?>/css/horadric.css" />
        <script type="text/javascript" src="<?= $this->theme_url ?>/js/jquery.js"></script>
        <script type="text/javascript" src="<?= $this->theme_url ?>/js/horadric.js"></script>
        <script type="text/javascript" src="http://horadric.info/powered/powered.js"></script>
    </head>


    <body>

<div id="wrapper">
    <div id="header">
        <div id="title"><a href="/"><img id="logo" src="<?= $this->theme_url ?>/img/cube.png" alt="Horadric.Info" /><strong>Horadric.Info</strong></a></div> <!-- #title -->
        <div id="subtitle">Diablo 3 Database</div> <!-- #subtitle -->
        <div class="horadric-ad" id="ad-leaderboard">
            <script type="text/javascript"><!--
            google_ad_client = "ca-pub-1492689034362877";
            /* Horadric.Info Leaderboard */
            google_ad_slot = "8070676987";
            google_ad_width = 728;
            google_ad_height = 90;
            //-->
            </script>
            <script type="text/javascript"
            src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
            </script>
        </div>
    </div>
    <div id="menu">
        <ul>
            <li<?php if (strpos($_SERVER['SCRIPT_URI'], '/news') !== FALSE) { print(' class="active"'); } ?>><a href="/news">Latest News</a></li>
            <li<?php if (strpos($_SERVER['SCRIPT_URI'], '/forums') !== FALSE) { print(' class="active"'); } ?>><a href="/forums">Forums</a></li>
            <li<?php if (strpos($_SERVER['SCRIPT_URI'], '/cain') !== FALSE) { print(' class="active"'); } ?>><a href="/cain">Blue Posts</a></li>
            <li<?php if (strpos($_SERVER['SCRIPT_URI'], '/class') !== FALSE || strpos($_SERVER['SCRIPT_URI'], '/classes') !== FALSE) { print(' class="active"'); } ?>><a href="/classes">Classes</a></li>
            <li<?php if (strpos($_SERVER['SCRIPT_URI'], '/items') !== FALSE || strpos($_SERVER['SCRIPT_URI'], '/item') !== FALSE) { print(' class="active"'); } ?>><a href="/items">Items</a></li>
            <li<?php if (strpos($_SERVER['SCRIPT_URI'], '/titles') !== FALSE) { print(' class="active"'); } ?>><a href="/titles">Titles</a></li>
            <li<?php if (strpos($_SERVER['SCRIPT_URI'], '/crafting') !== FALSE) { print(' class="active"'); } ?>><a href="/crafting">Crafting and Artisans</a></li>
            <li<?php if (strpos($_SERVER['SCRIPT_URI'], '/guides') !== FALSE) { print(' class="active"'); } ?>><a href="/guides">Guides</a></li>
<?php
$_auth = new Horadric_Authenticator();
if ($_auth->user_is_authenticated())
{
    $_user = $_auth->get_user_account();
    ?>
            <li class="account"><em>Hello, <strong><?= $_user->username ?></strong></em> &nbsp;|&nbsp;  <a href="/logout">Logout</a></li>
    <?php
} else {
    ?>
    <li class="account"><a href="/register">Register</a> &nbsp;|&nbsp; <a href="/login">Login</a></li>
    <?php
}
?>
        </ul>
    </div>
    <div id="container">
        <div id="sidebar">

            <h2>Latest News</h2>
            <div class="sidebar-section" id="sidebar-news">
                <dl>
                <?php $_model = new Forum_Model(); ?>
                <?php $_news = $_model->get_forum_threads(array('forum_id' => 6, 'order_by' => array('date_created', 'desc'), 'limit' => 5)); ?>
                <?php foreach ($_news as $_story): ?>
                    <dt><a href="/forums/thread/<?= $_story->url ?>"><?= $_story->title ?></a></dt>
                    <dd><?= $_forum_view->display_small_post_vote($_story) ?> | <?= date('M j g:ia', $_story->date_created) ?> by 
                        <?php if (empty($_story->author_id)): ?>
                            Anonymous
                        <?php else: ?>
                            <a href="/members/<?= $_story->author_id ?>"><?= $_story->author_name ?></a>
                        <?php endif; ?>
                    </dd>
                <?php endforeach; ?>
                </dl>
            </div> <!-- #sidebar-news -->

            <h2>Latest Posts</h2>
            <div class="sidebar-section" id="sidebar-posts">
                <dl>
                <?php $_model = new Forum_Model(); ?>
                <?php $_posts = $_model->get_forum_posts(array('order_by' => array('date_created', 'desc'), 'limit' => 5)); ?>
                <?php foreach ($_posts as $_post): ?>
                    <dt><a href="/forums/thread/<?= $_post->thread_url ?>"><?= $_post->title ?></a></dt>
                    <dd><?= $_forum_view->display_small_post_vote($_post) ?> | <?= date('M j g:ia', $_post->date_created) ?> by 
                        <?php if (empty($_post->author_id)): ?>
                            Anonymous
                        <?php else: ?>
                            <a href="/members/<?= $_post->author_id ?>"><?= $_post->author_name ?></a>
                        <?php endif; ?>
                    </dd>
                <?php endforeach; ?>
                </dl>
            </div> <!-- #sidebar-news -->
            <h2>Blue Posts</h2>
            <div class="sidebar-section" id="sidebar-blues">
                <ul>
                <?php foreach ($tmp_blues as $tmp_blue): ?>
                    <li><a href="/cain/<?= $tmp_blue->id ?>"><?= $tmp_blue->title ?></a></li>
                <?php endforeach; ?>
                </ul>
            </div> <!-- #sidebar-blues -->

            <div class="horadric-ad" id="ad-sidebar">
                <script type="text/javascript"><!--
                google_ad_client = "ca-pub-1492689034362877";
                /* Horadric - Sidebar Box */
                google_ad_slot = "3639003442";
                google_ad_width = 300;
                google_ad_height = 250;
                //-->
                </script>
                <script type="text/javascript"
                src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
                </script>
            </div> <!-- #sidebar-ad -->
        </div> <!-- #sidebar -->
        <div id="body">

            <div id="social-media"><span  class='st_facebook' ></span><span  class='st_twitter' ></span><span  class='st_reddit' ></span><span  class='st_digg' ></span><span  class='st_email' ></span></div>
