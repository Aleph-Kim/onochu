<?
require '../vendor/autoload.php';

require '../core/Controller.php';
require '../core/Model.php';

require_once '../config/config.php';

// path 체크
$path = isset($_GET['path']) ? $_GET['path'] : 'home/main';

// xss 방지
$path = htmlspecialchars($path, ENT_QUOTES, 'UTF-8');

// 뷰 파일 경로 구성
$viewFile = $_SERVER['VIEW_PATH'] . $path . '.php';

if (file_exists($viewFile)) {
    require $viewFile;
} else {
    http_response_code(404);
    require $_SERVER['ERROR_PATH'] . "404.php";
}
