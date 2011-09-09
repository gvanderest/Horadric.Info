<?php
/**
 * View a News Story
 */

$title = $story->title;
$description = htmlentities(strip_tags($story->excerpt));

include_once($this->theme_path . '/inc/header.php');

$avatar_path = EXO_BASE_PATH . '/assets/user_avatars/' . strtolower($story->author) . '.gif';
$avatar_url = EXO_BASE_URL . '/assets/user_avatars/' . strtolower($story->author) . '.gif';
$default_avatar_url = '/assets/user_avatars/default.gif';
?>

<h1><?= $story->title ?></h1>
<div id="news-story full">
    <div class="news-story">
        <div class="news-story-author">
            <div class="news-story-author-avatar"><img src="<?= file_exists($avatar_path) ? $avatar_url : $default_avatar_url ?>" alt="<?= $story->author ?>" /></div>
            <div class="news-story-author-name"><a href="mailto:<?= $story->email ?>"><?= $story->author ?></a></div>
        </div>
        <div class="news-story-content">
            <div class="news-story-date"><?= date('F jS, Y g:i:sa', $story->date_posted) ?></div>
            <?= Horadric_View::display_article_content($story->body); ?>
        </div>
    </div>
    <div class="news-comments">
        <?= Horadric_View::display_facebook_thread() ?>
    </div>
</div>

<?php 
include_once($this->theme_path . '/inc/footer.php');
