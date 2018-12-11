
<?php
spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});
require 'Controller.php';
// $pdo=new PDO('pgsql:host=localhost; dbname=BattleShip', 'artur','Oprznogr1');
$controller=new Controller;
$controller->setController();
?>
