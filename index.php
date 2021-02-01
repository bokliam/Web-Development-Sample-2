<?php
require 'lib/site.inc.php';
if(!isset($_SESSION[LOGIN_SESSION]['user'])){
    $root = $site->getRoot();
    header("location: $root/login.php");
}
$view = new Noir\HomeView($site, $user);
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