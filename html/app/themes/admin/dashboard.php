<?php
/**
 * CMS Admin Dashboard
 * @author Guillaume VanderEst <gui@exodusmedia.ca>
 * @package cms
 */
?>

<h1>CMS Admin</h1>
<h2>Dashboard</h2>
<p>Hello, <strong><?= $user->name ?></strong></p>
<p><?= link_to_self(array('logout'), 'Log Out'); ?></p>
