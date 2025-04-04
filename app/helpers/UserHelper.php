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
}
