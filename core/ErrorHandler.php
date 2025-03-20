<?

class ErrorHandler
{
    private static $logFile;

    private static $errorLevels = [
        E_ERROR => 'E_ERROR', // 1
        E_WARNING => 'E_WARNING', // 2
        E_PARSE => 'E_PARSE', // 4
        E_NOTICE => 'E_NOTICE', // 8
        E_CORE_ERROR => 'E_CORE_ERROR', // 16
        E_CORE_WARNING => 'E_CORE_WARNING', // 32
        E_COMPILE_ERROR => 'E_COMPILE_ERROR', // 64
        E_COMPILE_WARNING => 'E_COMPILE_WARNING', // 128
        E_USER_ERROR => 'E_USER_ERROR', // 256
        E_USER_WARNING => 'E_USER_WARNING', // 512
        E_USER_NOTICE => 'E_USER_NOTICE', // 1024
        E_STRICT => 'E_STRICT', // 2048
        E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR', // 4096
        E_DEPRECATED => 'E_DEPRECATED', // 8192
        E_USER_DEPRECATED => 'E_USER_DEPRECATED' // 16384
    ];

    // 치명적인 에러 레벨
    private static $fatalErrors = [
        E_ERROR,
        E_PARSE,
        E_CORE_ERROR,
        E_COMPILE_ERROR,
        E_USER_ERROR,
        E_RECOVERABLE_ERROR
    ];

    /**
     * 에러 핸들러 초기 설정
     *
     * @param string $logFile 로그 파일 경로 (기본값: 'error_log.txt')
     * @return void
     */
    public static function init($logFile = 'error_log.txt')
    {
        self::$logFile = $logFile;

        // 브라우저 에러 표시 비활성화
        ini_set('display_errors', 0);
        // 에러 로깅 활성화
        ini_set('log_errors', 1);
        // 로그 파일 지정
        ini_set('error_log', self::$logFile);

        // 커스텀 핸들러 설정
        set_error_handler([self::class, 'handleError']);
        set_exception_handler([self::class, 'handleException']);
    }

    /**
     * 에러를 로그 파일에 기록하는 함수
     *
     * @param int $errno 에러 번호
     * @param string $errstr 에러 메시지
     * @param string $errfile 에러가 발생한 파일
     * @param int $errline 에러가 발생한 줄
     * @return bool 성공 여부
     */
    public static function handleError($errno, $errstr, $errfile, $errline)
    {
        // 치명적인 에러가 아닌 경우
        if (!in_array($errno, self::$fatalErrors)) {
            return false; // 기본 PHP 에러 처리로 넘김
        }

        $date = date('Y-m-d H:i:s');
        $errorLevel = self::$errorLevels[$errno] ?? 'UNKNOWN_ERROR';
        $logMessage = "[{$date}] 오류 발생: {$errorLevel}\n메시지: {$errstr}\n파일: {$errfile}\n줄: {$errline}\n\n";
        error_log($logMessage, 3, self::$logFile);
        return true;
    }

    /**
     * 예외를 로그 파일에 기록하는 함수
     *
     * @param Throwable $exception 발생한 예외 객체
     */
    public static function handleException($exception)
    {
        $date = date('Y-m-d H:i:s');
        $logMessage = "[{$date}] 예외 발생: {$exception}\n\n";
        error_log($logMessage, 3, self::$logFile);
        
        // 에러 로깅 후 서버 에러 페이지 출력
        self::showErrorPage(500);
    }

    /**
     * 에러 코드에 따른 에러 페이지 출력
     *
     * @param int $code HTTP 에러 코드 (예: 500)
     * @return void
     */
    public static function showErrorPage($code)
    {
        switch ($code) {
            case 400:
                ScriptHelper::msgBack("잘못된 요청입니다.");
                break;
            default:
                http_response_code($code);
                require $_SERVER['ERROR_PATH'] . $code . '.php';
                break;
        }
        exit;
    }
}

// 파일 로드 시 자동으로 init 실행
ErrorHandler::init();
