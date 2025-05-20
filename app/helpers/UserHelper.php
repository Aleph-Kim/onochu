<?
class UserHelper
{
    /**
     * 접속 기기의 모바일 여부를 반환하는 함수
     * 
     * @return boolean - 모바일 여부
     */
    public static function isMobile()
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $pattern = '/android|samsung|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i';
        return preg_match($pattern, $user_agent);
    }

    /**
     * 접속 기기의 운영체제가 안드로이드인지 확인하는 함수
     * 
     * @return boolean - 안드로이드 여부
     */
    public static function isAndroid()
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $pattern = '/android|samsung/i';
        return preg_match($pattern, $user_agent);
    }

    /**
     * 로그인 여부를 확인하는 함수
     * 
     * @return boolean - 로그인 여부
     */
    public static function checkLogin()
    {
        return isset($_SESSION['user']);
    }

    /**
     * 로그인 페이지로 이동하는 함수
     */
    public static function sendLogin()
    {
        setcookie('last_url', $_SERVER['REQUEST_URI'], time() + 3600, '/');
        ScriptHelper::msgGo("로그인 후 이용해주세요.", "/login");
    }

    /**
     * 마지막 접속 페이지로 이동하는 함수
     */
    public static function sendLastUrl()
    {
        setcookie('last_url', null, -1, '/');
        ScriptHelper::go($_COOKIE['last_url']);
    }

    /**
     * https 요청 여부를 확인하는 함수
     * 
     * @return boolean - https 여부
     */
    public static function isHttps()
    {
        // X-Forwarded-Scheme 확인
        if (!empty($_SERVER['HTTP_X_FORWARDED_SCHEME']) && $_SERVER['HTTP_X_FORWARDED_SCHEME'] === 'https') {
            return true;
        }
        // 기본 HTTPS 변수 확인
        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
            return true;
        }
        // REQUEST_SCHEME 확인
        if (!empty($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] === 'https') {
            return true;
        }
        return false;
    }
}
