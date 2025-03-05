<?
require '../vendor/autoload.php';

require '../core/Controller.php';
require '../core/Model.php';

// path 체크
$path = isset($_GET['path']) ? $_GET['path'] : 'home/main';

// xss 방지
$path = htmlspecialchars($path, ENT_QUOTES, 'UTF-8');

// 뷰 파일 경로 구성
$viewFile = '../app/views/' . $path . '.php';

if (file_exists($viewFile)) {
    require $viewFile;
} else {
    http_response_code(404);
    echo "404 Not Found";
}
