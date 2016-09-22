<?php
$url = $_SERVER['HTTP_REFERER'];
$theme = $_GET['theme'];
if($theme == 'night' || $theme == 'light') setcookie('theme', $theme, time() + 3600*12);
header("Location: " . $url);
?>