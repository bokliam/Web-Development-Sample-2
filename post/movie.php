<?php
require '../lib/site.inc.php';
$user = $_SESSION[LOGIN_SESSION]['user'];
$controller = new Noir\MovieController($site, $user, $_POST);
header("location: " . $controller->getRedirect());
//echo $controller->linkRedirect();
