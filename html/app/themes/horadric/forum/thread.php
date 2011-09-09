<?php
/**
 * Display a thread
 * @author Guillaume VanderEst <gui@exoduslabs.ca>
 */

$_title = $thread->title;
$_description = htmlentities(truncate(strip_tags($this->parse_post($thread->body)), 300));
require_once($this->theme_path . '/forum/inc/header.php');
?>

<h1><a href="/forums/thread/<?= $thread->url ?>"><?= $thread->title ?></a></h1>
<div class="breadcrumb">
    <ul>
        <li><a href="/forums">Forums</a></li>
        <li><a href="/forums/forum/<?= $forum->url ?>"><?= $forum->name ?></a></li>
    </ul>
</div>

<div class="forum-thread">
<?= $this->display_pagination($page, $pages) ?>
    <?php foreach ($posts as $post): ?>
    <?php $_avatar_path = $_SERVER['DOCUMENT_ROOT'] . '/assets/user_avatars/' . $post->author_avatar . '.gif'; ?>
    <?php $_avatar_url = '/assets/user_avatars/' . $post->author_avatar . '.gif'; ?>
    <?php $_default_avatar_url = '/assets/user_avatars/default.gif'; ?>
    <?php $_anonymous_avatar_url = '/assets/images/anonymous_avatar.gif'; ?>
    <div class="post<?= empty($post->author_id) ? ' anonymous' : '' ?>"> 
        <div class="post-author"> 
            <?php if (empty($post->author_id)): ?>
                <div class="author-avatar"><img src="<?= $_anonymous_avatar_url ?>" alt="Anonymous" /></div> 
                <div class="author-name">Anonymous</div> 
            <?php else: ?>
                <div class="author-avatar"><img src="<?= file_exists($_avatar_path) ? $_avatar_url : $_default_avatar_url ?>" alt="<?= $post->author_name ?>" /></div> 
                <div class="author-name"><a href="/members/<?= $post->author_name ?>"><?= $post->author_name ?></a></div> 
                <div class="author-level">Level: <?= Horadric_Model::get_level_from_experience($post->author_experience) ?></div>
                <?php if (!empty($post->author_title)): ?><div class="author-title"><?= $post->author_title ?></div><?php endif; ?>
                <?php if (!empty($post->author_guild)): ?><div class="author-guild">&lt;<?= $post->author_guild ?>&gt;</div><?php endif; ?>
            <?php endif; ?>
        </div> 
        <div class="post-content"> 
            <div class="post-date">
                <?= $this->display_post_vote($post) ?> &nbsp; <?= date('F jS, Y g:ia', $post->date_created) ?>
            </div> 
             
            <?= $this->parse_post($post->body) ?>
            <?php if (!empty($post->author_signature)): ?>
                <div class="author-signature"><?= $this->parse_post($post->author_signature) ?></div>
            <?php endif; ?>

        </div> 
    </div> 
<?php endforeach; ?>
<?= $this->display_pagination($page, $pages) ?>

<div class="reply-form">
    <h2>Post Reply</h2>
    <?php if ($thread->locked || $forum->locked): ?>
        <p>New replies cannot be created in a locked thread.</p>
    <?php else: ?>
        <?php if (!$account && !FORUM_ALLOW_ANONYMOUS_REPLIES): ?>
            <p>You must be logged in to create a reply.</p>
        <?php else: ?>
            <?php if ($reply_form->submitted()): ?>
                <?= $reply_form->errors->display(); ?>
            <?php endif; ?>
            <?php if (!$account): ?>
                <p class="anonymous-reply-note">This post will be made anonymously</p>
            <?php endif; ?>
            <?= $reply_form->display(); ?>
        <?php endif; ?>
    <?php endif; ?>
</div>

</div>

<?php
require_once($this->theme_path . '/forum/inc/footer.php');
