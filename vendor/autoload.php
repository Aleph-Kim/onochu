<?

function autoload($class_name)
{
    require './app/controllers/' . $class_name . '.php';
}
spl_autoload_register('autoload');
