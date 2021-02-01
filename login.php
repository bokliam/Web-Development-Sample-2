<?php
$open = true;
require 'lib/site.inc.php';
$view = new Noir\LoginView($site, $_COOKIE);
$view->setTitle("Ajax Noir Login");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php echo $view->head(); ?>
</head>
<body>
<?php
echo $view->header();
echo $view->present();
echo $view->footer();
?>

</body>
</html>