<?php
/**
 * Admin Login Template
 * @author Guillaume VanderEst <gui@exodusmedia.ca>
 */
?>
<!DOCTYPE html>
<html>
    <head>
        <title>CMS Login</title>
        <meta charset="utf-8" />
        <meta name="robots" content="noindex,nofollow" />
<style type="text/css">
</style>
    </head> 
    <body>

<div id="wrapper">
    <div id="container">
        <div id="header">
            <h1>Sympo<strong>CM</strong></h1>
            <h2>Content Management System Login</h2>
        </div> <!-- #header -->
        <div id="body">
            <div id="login_box">
                <form id="login_form" method="post" action="">
                <?php
                /**
                 * Stub
                 */
if ($_SERVER['REQUEST_METHOD'] == 'POST' && count($errors) > 0)
{
?>
<div class="ExoUI_Error">
    <p>The following errors were encountered:</p>
    <ul>
        <?php foreach ($errors as $error): ?>
        <li><?= $error ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php
}
?>
                    <input type="hidden" name="_form" value="login_form" />
                    <input type="hidden" name="_xsstoken" value="<?= md5(time()); ?>" />
                    <div class="ExoUI_Textbox">
                        <label for="login_form_username">Username</label>
                        <input type="textbox" name="username" id="login_form_username" />
                    </div>
                    <div class="ExoUI_Password">
                        <label for="login_form_password">Password</label>
                        <input type="password" name="password" id="login_form_password" />
                    </div>
                    <div class="ExoUI_Checkbox">
                        <div class="options">
                            <div class="option"><input type="checkbox" name="remember" id="login_form_remember_1" /><label for="login_form_remember_1">Remember Login</label></div>
                        </div>
                    </div>
                    <div class="ExoUI_Submit">
                        <input type="submit" name="submit" id="login_form_submit" value="Login" />
                    </div>
                </form>
            </div> <!-- #login -->
        </div> <!-- #body -->
        <div id="footer">
            <div id="copyright">Copyright &copy; <?= date('Y'); ?> <a href="http://www.exodusmedia.ca" target="_blank">Exodus Media</a></div> <!-- #copyright -->
            <div id="powered-by">Powered by <a href="http://www.sympocm.com" target="_blank">SympoCM</a></div> <!-- #powered -->
        </div>
    </div> <!-- #container -->
</div> <!-- #wrapper -->

    </body>
</html>
