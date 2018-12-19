
<?php
spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});
require 'Controller.php';
$pdo=new PDO('pgsql:host=localhost; dbname=postgres', 'rodion','qwerty1234');
$controller=new Controller;
$controller->setController($pdo);
?>
