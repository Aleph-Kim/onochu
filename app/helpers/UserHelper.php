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
