<?
@session_start();
require_once '../vendor/autoload.php';

require_once '../config/config.php';

require_once $_SERVER['CONFIG_PATH'] . "loadEnv.php";
require_once $_SERVER['CORE_PATH'] . "ErrorHandler.php";
require_once $_SERVER['CORE_PATH'] . 'Controller.php';
require_once $_SERVER['CORE_PATH'] . 'Model.php';
require_once $_SERVER['CORE_PATH'] . 'Redis.php';

require_once $_SERVER['HELPER_PATH'] . 'FloApiHelper.php';
require_once $_SERVER['HELPER_PATH'] . 'ScriptHelper.php';
require_once $_SERVER['HELPER_PATH'] . 'UserHelper.php';

// 한국 시간대 설정
date_default_timezone_set('Asia/Seoul');

// path 체크
$path = isset($_REQUEST['path']) ? $_REQUEST['path'] : 'home/main';

// xss 방지
$path = htmlspecialchars($path, ENT_QUOTES, 'UTF-8');

// 뷰 파일 경로 구성
$viewFile = $_SERVER['VIEW_PATH'] . $path . '.php';

// 경로가 디렉토리일 경우 index.php를 추가
$fullPath = $_SERVER['VIEW_PATH'] . $path;
if (is_dir($fullPath)) {
    $viewFile = rtrim($fullPath, '/') . '/index.php';
}

if (file_exists($viewFile)) {
    require $viewFile;
} else {
    ErrorHandler::showErrorPage(404);
}
