<?
class ErrorHandler
{
    /**
     * 에러 코드에 따른 에러 페이지 출력
     *
     * @param int $code HTTP 에러 코드 (예: 500)
     */
    public static function handleError($code)
    {
        http_response_code($code);
        require $_SERVER['ERROR_PATH'] . $code . '.php';
        exit;
    }
}
