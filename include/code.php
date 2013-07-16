<?php
require_once('code.class.php');
session_start();
$img = new captcha('data/captcha/');
$img->generate_image();


?>
