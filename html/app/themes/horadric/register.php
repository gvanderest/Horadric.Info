<?php
/**
 * Login Form
 * @author Guillaume VanderEst <gui@exoduslabs.ca>
 * @package horadric
 */

require_once($this->theme_path . '/inc/header.php');
?>

<h1>Registration</h1>
<p>In order to create an account at Horadric.Info, you will need to fill out the following form:</p>
<?php if ($form->submitted()): ?>
    <?= $form->errors->display(); ?>
<?php endif; ?>
<?= $form->display(); ?>

<?php
require_once($this->theme_path . '/inc/footer.php');
