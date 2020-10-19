<?php
require '../config/config.php';
session_start();
// logout.php, de sessie vernietigen en doorsturen naar de homepage
session_unset();
session_destroy();
header('Location: '.$rootURL.'/static');

