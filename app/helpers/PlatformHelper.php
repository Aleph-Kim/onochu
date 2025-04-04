<?
class PlatformHelper
{
    /**
     * 각 플랫폼별 노래 url을 반환하는 함수
     * 
     * 유튜브 - 노래 검색 페이지 url
     * 지니뮤직 - 모바일일 경우 지니뮤직 앱 url 스키마 || pc일 경우 노래 검색 페이지 url
     * 플로뮤직 - 모바일일 경우 노래 상세페이지 앱 url 스키마 || pc일 경우 노래 상세 페이지 url
     * 
     * @param array $song_info flo api에서 받아온 노래 정보
     * @return array 각 플랫폼별 노래 url
     */
    public static function getPlatformUrl($song, $artists)
    {
        // 사용자 접속환경 모바일 여부
        $is_mobile = UserHelper::isMobile();
        $is_android = UserHelper::isAndroid();
        // 검색 시 키워드
        $artists_name = implode(' ', array_column($artists, 'name'));
        $keyword = "{$song['title']} {$artists_name}";
        // 플로에서 사용하는 노래 pk
        $flo_id = $song['flo_id'];

        if ($is_mobile) {
            $youtube = $is_android ? $_SERVER['YOUTUBE_MUSIC_ANDROID_APP_PATH']($keyword) : $_SERVER['YOUTUBE_MUSIC_IOS_APP_PATH']($keyword);
            $genie = $_SERVER['GENIE_APP_PATH'];
            $flo = $_SERVER['FLO_APP_DETAIL_PATH']($flo_id);
        } else {
            $youtube = $_SERVER['YOUTUBE_MUSIC_SEARCH_PATH']($keyword);
            $genie = $_SERVER['GENIE_SEARCH_PATH']($keyword);
            $flo = $_SERVER['FLO_DETAIL_PATH']($flo_id);
        }
        return [
            'youtube' => $youtube,
            'genie' => $genie,
            'flo' => $flo,
        ];
    }
}
