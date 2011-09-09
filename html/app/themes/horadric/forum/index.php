<?php
/**
 * Display the forum index, listing all categories and forums
 * @author Guillaume VanderEst <gui@exoduslabs.ca>
 */

$_title = 'Forum Index';
$_description = 'Listing of the forums available for discussion, including: Class Discussion, General Discussion, and the various Off-Topic Boards';
require_once($this->theme_path . '/forum/inc/header.php');
?>

<h1>Forums</h1>

<table class="forum-index">
<?php foreach ($categories as $category): ?>

    <tbody class="forum-category">
        <tr>
            <th class="category-name" colspan="4"><?= $category->name ?></th>
        </tr>
        <tr class="category-heading">
            <th class="forum-icon"><!-- icon --></th>
            <th class="forum-name">Forum Name</th>
            <th class="forum-stats">Stats</th>
            <th class="forum-latest-post">Latest Post</th>
        </tr>
    <?php foreach ($forums as $forum): ?>
        <?php if ($forum->category_id == $category->id): ?>
        <?php
        if ($forum->locked) { $icon = 'locked'; $title = 'Locked Forum'; }
        elseif ($forum->thread_locked) { $icon = 'thread-locked'; $title = 'New Threads Locked'; }
        elseif (time() - $forum->latest_post_date < 60*60*24) { $icon = 'active'; $title = 'Active Forum'; }
        else { $icon = 'normal'; $title = 'Normal Forum'; }
        ?>
        <tr>
            <td class="forum-icon"><img src="<?= $this->theme_url ?>/img/forum-<?= $icon ?>.png" title="<?= $title ?>" /></td>
            <td class="forum-name"><a href="/forums/forum/<?= $forum->url ?>"><?= $forum->name ?></a><?= !empty($forum->description) ? '<br /><span class="forum-description">' . $forum->description . '</span>' : '' ?></td>
            <td class="forum-stats"><?= $forum->thread_count ? $forum->thread_count : 0 ?> <?= s($forum->thread_count, 'Thread') ?><br /><?= $forum->reply_count ? $forum->reply_count : 0 ?> <?= s($forum->reply_count, 'Reply', 'Replies') ?></td>
            <?php if ($forum->latest_post_id): ?>
            <td class="forum-latest-post"><a class="title" href="/forums/thread/<?= $forum->latest_post_thread_url ?>"><?= $forum->latest_post_title ?></a> by 
            <?php if (empty($forum->latest_post_author_name)): ?>
                Anonymous
            <?php else: ?>
                <a class="author" href="/members/<?= $forum->latest_post_author_name ?>"><?= $forum->latest_post_author_name ?></a>
            <?php endif; ?>
            <br /><span class="date"><?= date('F jS, Y g:ia', $forum->latest_post_date); ?></span></td>
            <?php else: ?>
            <td class="forum-latest-post empty">No posts found</td>
            <?php endif; ?>
        </tr>
        <?php endif; ?>
    <?php endforeach; ?>
    </tbody>
<?php endforeach; ?>
</table>

<div class="forum-legend">
<h3>Legend</h3>
<table>
    <?php foreach (array('locked' => 'Locked Forum - New threads and posts cannot be made', 'thread-locked' => 'Thread Locked Forum - New threads cannot be made', 'active' => 'Active Forum - A new reply has been made in the past 24 hours', 'normal' => 'Open Forum - Normal forum with all options available') as $icon => $title): ?>
    <tr>
        <td><img src="<?= $this->theme_url ?>/img/forum-<?= $icon ?>.png" alt="" /></td>
        <td><?= $title ?></td>
    </tr>
    <?php endforeach; ?>
</table>
</div>

<?php
require_once($this->theme_path . '/forum/inc/footer.php');
