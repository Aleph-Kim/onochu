<?

// env 파일 경로
$filePath = $_SERVER['ENV_PATH'];

// 파일이 존재하지 않을 경우 예외처리
if (!file_exists($filePath)) {
    ErrorHandler::handleError(500);
}

// 파일 읽기
$lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($lines as $line) {
    // 주석(#) 무시
    if ($line === '' || strpos($line, '#') === 0) continue;

    // '='가 없는 경우 예외 처리
    if (!strpos($line, '=')) continue;

    list($key, $value) = explode('=', $line, 2);
    $key = trim($key);
    $value = trim($value);

    // 환경 변수 설정(중복 방지)
    if (!array_key_exists($key, $_ENV) && !array_key_exists($key, $_SERVER)) {
        putenv("$key=$value");
    }
}

return true;
