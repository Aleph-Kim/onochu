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
        switch ($code) {
            case 400:
                self::msgBack("잘못된 요청입니다.");
                break;
            default:
                http_response_code($code);
                require $_SERVER['ERROR_PATH'] . $code . '.php';
        }
        exit;
    }

    /**
     * 에러 메시지를 출력한 후 뒤로가기
     */
    private function msgBack($msg)
    {
        $code = "<script>window.history.go(-1);alert('{$msg}');</script>";

        echo $code;
    }
}
