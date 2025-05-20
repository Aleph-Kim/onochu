<?

class AsyncHelper
{
    public static function setJsonRequest($method)
    {
        $_POST = json_decode(file_get_contents("php://input"), true);
        if (self::checkMethod($method)) {
            return;
        }

        self::returnJsonResponse([
            'message' => '잘못된 요청입니다.'
        ], 405);
        exit;
    }

    public static function returnJsonResponse($data, $code = 200)
    {
        header('Content-type: application/json');
        header('HTTP/1.1 ' . $code);
        echo json_encode($data);
    }

    public static function checkMethod($method)
    {
        return $_SERVER['REQUEST_METHOD'] == $method;
    }
}
