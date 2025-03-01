<?
require './vendor/autoload.php';

require './core/Controller.php';
require './core/Model.php';

$controller = new HomeController();
$controller->index();
