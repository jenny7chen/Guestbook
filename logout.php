<?php
require_once 'cookie_setting.php';
require_once 'post.php';
session_save_path(realpath(dirname($_SERVER['DOCUMENT_ROOT']).'/../session'));
$cookieSetting = new CookieSetting();
if (isset($_COOKIE['session_id']) && !empty($_COOKIE['session_id'])) {
    session_id($_COOKIE['session_id']);
}
session_start();
session_destroy();
header('Location: login.php');
exit;
