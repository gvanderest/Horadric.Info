<?php
/**
 * Login Form
 * @author Guillaume VanderEst <gui@exoduslabs.ca>
 * @package horadric
 */

require_once($this->theme_path . '/inc/header.php');
?>

<h1>Account Login</h1>
<?php if ($form->submitted()): ?>
    <?= $form->errors->display(); ?>
<?php endif; ?>

<?= $form->display(); ?>

<?php
require_once($this->theme_path . '/inc/footer.php');
