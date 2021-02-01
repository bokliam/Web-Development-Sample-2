<?php

require '../lib/site.inc.php';

$user = $_SESSION[LOGIN_SESSION]['user'];
$controller = new Noir\StarController($site, $user, $_POST);
echo $controller->getResult();