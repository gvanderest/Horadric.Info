<?php
/**
 * Display the forum index, listing all categories and forums
 * @author Guillaume VanderEst <gui@exoduslabs.ca>
 */

$_title = $forum->name;
$_description = $forum->description;
require_once($this->theme_path . '/forum/inc/header.php');
?>

<h1><a href="/forums/forum/<?= $forum->url ?>"><?= $forum->name ?></a></h1>
<div class="breadcrumb">
    <ul>
        <li><a href="/forums">Forums</a></li>
    </ul>
</div>
<?php if (!empty($forum->description)): ?>
    <p class="forum-description"><?= $forum->description ?></p>
<?php endif; ?>

<table class="forum-threads">
    <thead>
        <tr class="category-heading">
            <th class="thread-icon"><!-- icon --></th>
            <th class="thread-name">Title</th>
            <th class="thread-author">Author</th>
            <th class="thread-stats">Stats</th>
            <th class="thread-latest-reply">Latest Reply</th>
        </tr>
    </thead>
    <tbody>
    <?php if (count($threads) > 0): ?>
        <?php foreach ($threads as $thread): ?>
        <?php 
        if ($thread->locked) { $icon = 'locked'; $title = 'Locked Thread'; }
        elseif ($thread->sticky) { $icon = 'sticky'; $title = 'Sticky Thread'; }
        elseif ((time() - $thread->date_created < 24*60*60) || (time() - $thread->latest_reply_date < 24*60*60)) { $icon = 'active'; $title = 'Active Thread'; }
        else { $icon = 'normal'; $title = 'Normal Thread'; }

        $unread = ($account && ($thread->date_viewed < $thread->latest_reply_date || $thread->date_viewed < $thread->date_created));
        ?>
            <tr>
                <td class="thread-score"><?= $this->display_post_vote($thread) ?></td>
                <td class="thread-name"><?= $thread->sticky ? '<strong>' : '' ?><a href="/forums/thread/<?= $thread->url ?>"><?= $thread->title ?></a><?= $thread->sticky ? '</strong>' : '' ?>
                    <?= $unread ? '<a class="unread" href="/forums/thread/' . $thread->url . '/latest-unread">(Unread)</a>' : '' ?>
                </td>
                <?php if (empty($thread->author_id)): ?>
                    <td class="thread-author">Anonymous</td>
                <?php else: ?>
                    <td class="thread-author"><a href="/members/<?= $thread->author_name ?>"><?= $thread->author_name ?></a></td>
                <?php endif; ?>
                <td class="thread-stats"><?= (int)$thread->views ?> <?= s($thread->views, 'View') ?><br /><?= (int)$thread->reply_count ?> <?= s($thread->reply_count, 'Reply', 'Replies') ?></td>
                <?php if ($thread->latest_reply_id): ?>
                <td class="thread-latest-reply"><a class="title" href="/forums/thread/<?= $thread->url ?>/latest"><?= $thread->latest_reply_title ?></a> by 
                <?php if (empty($thread->latest_reply_author_id)): ?>
                    Anonymous
                <?php else: ?>
                    <a class="author" href="/members/<?= $thread->latest_reply_author_name ?>"><?= $thread->latest_reply_author_name ?></a>
                <?php endif; ?>
                <br /><span class="date"><?= date('F jS, Y g:ia', $thread->latest_reply_date); ?></span></td>
                <?php else: ?>
                <td class="thread-latest-reply empty">No replies found</td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="5" class="forum-empty">There are currently no threads in this forum</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>



    <div class="post-form">
        <h2>Post Thread</h2>
        <?php if ($forum->locked || $forum->thread_locked): ?>
            <p>New threads cannot be created in a locked forum.</p>
        <?php else: ?>
            <?php if (!$account && !FORUM_ALLOW_ANONYMOUS_THREADS): ?>
                <p>You must be logged in to create a thread.</p>
            <?php else: ?>
                <?php if ($thread_form->submitted()): ?>
                    <?= $thread_form->errors->display(); ?>
                <?php endif; ?>
                <?php if (!$account): ?>
                    <p class="anonymous-thread-note">This thread will be created anonymously</p>
                <?php endif; ?>
                <?= $thread_form->display(); ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>

<?php
require_once($this->theme_path . '/forum/inc/footer.php');
