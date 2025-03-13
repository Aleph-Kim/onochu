<?
require_once '../vendor/autoload.php';

require_once '../config/config.php';

require_once $_SERVER['CONFIG_PATH'] . "loadEnv.php";
require_once $_SERVER['CORE_PATH'] . "ErrorHandler.php";
require_once $_SERVER['CORE_PATH'] . 'Controller.php';
require_once $_SERVER['CORE_PATH'] . 'Model.php';

require_once $_SERVER['HELPER_PATH'] . 'FloApi.php';


// path 체크
$path = isset($_GET['path']) ? $_GET['path'] : 'home/main';

// xss 방지
$path = htmlspecialchars($path, ENT_QUOTES, 'UTF-8');

// 뷰 파일 경로 구성
$viewFile = $_SERVER['VIEW_PATH'] . $path . '.php';

if (file_exists($viewFile)) {
    require $viewFile;
} else {
    ErrorHandler::handleError(404);
}
