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
        $pattern = '/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/';
        $is_mobile = preg_match($pattern, $user_agent);
        return $is_mobile;
    }
}
