<?php
/**
 * View a D3 Item
 */

$title = 'Cain Community Watcher';
$description = 'This page displays the forum topics in the Battle.Net forums that were posted by or replied to by Blizzard staff. The more red a post\'s title is, the more recently it was posted.';

include_once($this->theme_path . '/inc/header.php');
?>

<div style="float: right; margin: 0 0 15px 25px;"><img src="/assets/images/cain.png" alt="Deckard Cain" /></div>
<h1>Cain Community Watcher</h1>
<h2>Diablo 3 Forum Threads</h2>
<p>This page displays the forum topics in the Battle.Net forums that were posted by or replied to by Blizzard staff.  The more <span style="font-weight: bold; color: #f00">red</span> a post's title is, the more recently it was posted.</p>

<?php
$blizz_icon = $this->theme_url . '/img/blizz.gif';
        if (count($threads) > 0)
        {
            ?>
            <ul>
            <?php foreach ($threads as $thread): ?>
                <?php $posts = $thread->posts(array('sort' => array('date_posted', 'asc'))); ?>
<?php
                $ap = Horadric_Application::get_age_percentage($posts[0]->date_posted);
                $apr = floor(255 - (150 * $ap));
                $apg = floor(100 * $ap);
                $apb = floor(100 * $ap);
                $aprgb = implode(',', array($apr, $apg, $apb));
?>
            <li><a style="color: rgb(<?= $aprgb; ?>); font-weight: <?= $ap <= 0.5 ? 'bold' : 'normal' ?>;" href="/cain/<?= $thread->id ?>"><?= $thread->title ?></a> by <strong class="author<?php if ($posts[0]->blizzard_posted) { print(' blue'); } ?>"><?= $posts[0]->author ?><?php if ($posts[0]->blizzard_posted) { print(' <img src="' . $blizz_icon . '" alt="Blizzard Representative" />'); } ?></strong> - <?= date('F jS, Y h:i:sa', $posts[0]->date_posted); ?>
                <?php if (FALSE && count($posts) > 0): ?>
                    <ul>
                    <?php $count = 0; foreach ($posts as $post): $count++; if ($count == 1) { continue; }?>
<?php 
            $postClasses = array(); 
            if ($post->blizzard_posted) { $postClasses[] = 'blizzard'; } 
            if (abs(time() - $post->date_posted) < 60*60*4) { $postClasses[] = 'brandnew'; } elseif (abs(time() - $post->date_posted) < 60*60*24) { $postClasses[] = 'new'; }

                $ap = Horadric_Application::get_age_percentage($post->date_posted);
                $apr = floor(255 - (150 * $ap));
                $apg = floor(100 * $ap);
                $apb = floor(100 * $ap);
                $aprgb = implode(',', array($apr, $apg, $apb));
?>
                        <li class="<?= implode(' ', $postClasses); ?>"><a style="color: rgb(<?= $aprgb; ?>);  font-weight: <?= $ap <= 0.5 ? 'bold' : 'normal' ?>;" href="/cain/<?= $thread->id ?>#post<?= $post->id ?>"><?= $post->title ?></a> by <strong class="author<?php if ($post->blizzard_posted) { print(' blue'); } ?>"><?= $post->author ?><?php if ($post->blizzard_posted) { print(' <img src="' . $blizz_icon . '" alt="Blizzard Representative" />'); } ?></strong> - <?= date('F jS, Y h:i:sa', $post->date_posted); ?></li>
                    <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                </li>
            <?php endforeach; ?>
            </ul>
            <?php
        }

?>
<p>Last Updated: <?= date('F jS, Y h:i:sa', $cache_age); ?></p>

<?php 
include_once($this->theme_path . '/inc/footer.php');
