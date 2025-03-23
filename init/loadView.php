<?

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
