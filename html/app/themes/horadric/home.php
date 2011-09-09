<?php
/**
 * View a D3 Item
 */

$title = 'Diablo 3 Item, NPC and Quest Database';
$description = 'A community-driven database of information on Diablo 3 and the many elements that make it up.  News, lore, classes, items, NPCs, quests, and more!';

include_once($this->theme_path . '/inc/header.php');

$_forum_view = new Forum_View();
?>

<h1>Latest News</h1>

<div id="home-news">
    <?php foreach ($news as $story): ?>
    <?php $_avatar_path = $_SERVER['DOCUMENT_ROOT'] . '/assets/user_avatars/' . $story->author_avatar . '.gif'; ?>
    <?php $_avatar_url = '/assets/user_avatars/' . $story->author_avatar . '.gif'; ?>
    <?php $_default_avatar_url = '/assets/user_avatars/default.gif'; ?>
    <?php
    $story->excerpt = NULL;
    if (preg_match('/\[excerpt\](.+?)\[\/excerpt\]/sim', $story->body, $_matches))
    {
        $story->excerpt = $_matches[1];
    }
    ?>
    <div class="news-story"> 
        <div class="news-story-author"> 
            <div class="author-avatar"><img src="<?= file_exists($_avatar_path) ? $_avatar_url : $_default_avatar_url ?>" alt="<?= $post->author_name ?>" /></div> 
            <div class="author-name"><a href="/members/<?= $story->author_name ?>"><?= $story->author_name ?></a></div> 
        </div> 
        <div class="news-story-content"> 
            <h2><a href="/forums/thread/<?= $story->url ?>"><?= $story->title ?></a></h2>
            <div class="post-date"><?= $_forum_view->display_post_vote($story) ?> &nbsp; <?= date('F jS, Y g:ia', $story->date_created) ?></div> 

            <?php if (!empty($story->excerpt)): ?>
            <?= Forum_View::parse_post($story->excerpt) ?>
            <a href="/forums/thread/<?= $story->url ?>">Read More ..</a>
            <?php else: ?>
            <?= Forum_View::parse_post($story->body) ?>
            <?php endif; ?>

        </div> 
    </div> 
    <?php endforeach; ?>
</div> <!-- #home-news -->

<?php 
include_once($this->theme_path . '/inc/footer.php');
