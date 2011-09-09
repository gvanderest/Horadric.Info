<?php
/**
 * View a Cain Thread
 */

$posts = $thread->posts(array('sort' => array('date_posted', 'asc')));

$title = htmlentities($thread->title);
$description = strip_tags(htmlentities($posts[0]->content));

include_once($this->theme_path . '/inc/header.php');
?>

<h1><?= $thread->title ?></h1>
<p><a href="/cain">Return to Thread Index</a></p>

<?php foreach ($posts as $post): ?>
<?php
$post_classes = array('cain-post');
if ($post->blizzard_posted) { $post_classes[] = 'blue'; }

$default_avatar_url = EXO_BASE_URL . '/assets/avatars/default.gif';
if ($post->blizzard_posted) { $default_avatar_url = EXO_BASE_URL . '/assets/avatars/blizzard_default.gif'; }
$avatar_path = EXO_BASE_PATH . '/assets/avatars/' . strtolower($post->author) . '.gif';
$avatar_url = EXO_BASE_URL . '/assets/avatars/' . strtolower($post->author) . '.gif';
?>
<div class="<?= implode(' ', $post_classes) ?>" id="post<?= $post->id ?>">
    <div class="cain-post-author">
        <div class="cain-post-author-avatar"><img src="<?= file_exists($avatar_path) ? $avatar_url : $default_avatar_url ?>" alt="<?= $post->author ?>'s avatar" /></div>
        <div class="cain-post-author-name"><?= $post->author ?></div>
        <?= $post->blizzard_posted ? '<div class="cain-post-blizzard-icon"><img src="' . $this->theme_url . '/img/blizz.gif' . '" alt="Blizzard Representative" /></div>' : '' ?>
    </div>
    <div class="cain-post-content">
        <!--<h2><?= $post->title ?></h2>-->
        <div class="cain-post-date"><?= date('F jS, Y g:i:sa', $post->date_posted) ?> (<a href="<?= $post->url ?>" target="_blank">Source</a>)</div>
        <p><?= nl2br($post->content) ?></p>
    </div>
</div>
<?php endforeach; ?>

<?php 
include_once($this->theme_path . '/inc/footer.php');
