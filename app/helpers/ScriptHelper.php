<?
class ScriptHelper
{
    /**
     * 메시지를 출력한 후 페이지 이동
     */
    public static function msgGo($msg, $url = '/')
    {
        $code = "<script>window.location.href = '{$url}';alert('{$msg}');</script>";

        echo $code;
    }

    /**
     * 메시지를 출력한 후 뒤로가기
     */
    public static function msgBack($msg)
    {
        $code = "<script>window.history.go(-1);alert('{$msg}');</script>";

        echo $code;
    }

    public static function go($url)
    {
        header("Location: {$url}");
        exit;
    }
}
