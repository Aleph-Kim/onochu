<?

/**
 * 내부 경로
 */
// core 경로
$_SERVER['CORE_PATH'] = $_SERVER['DOCUMENT_ROOT'] . "/core/";

// config 경로
$_SERVER['CONFIG_PATH'] = $_SERVER['DOCUMENT_ROOT'] . "/config/";

// env 파일 경로
$_SERVER['ENV_PATH'] = $_SERVER['DOCUMENT_ROOT'] . "/.env";

// Helpers 경로
$_SERVER['HELPER_PATH'] = $_SERVER['DOCUMENT_ROOT'] . "/app/helpers/";

// 뷰 경로
$_SERVER['VIEW_PATH'] = $_SERVER['DOCUMENT_ROOT'] . "/app/resources/views/";

// 레이아웃 경로
$_SERVER['LAYOUT_PATH'] = $_SERVER['DOCUMENT_ROOT'] . "/app/resources/layout/";

// js 경로
$_SERVER['JS_PATH'] = "/app/resources/assets/js/";

// css 경로
$_SERVER['CSS_PATH'] = "/app/resources/assets/css/";

// image 경로
$_SERVER['IMAGE_PATH'] = "/app/resources/assets/image/";

// 에러페이지 뷰 경로
$_SERVER['ERROR_PATH'] = $_SERVER['VIEW_PATH'] . "error/";

/**
 * 외부 경로 
 */
// flo 검색 api 경로
$_SERVER['FLO_API_SEARCH_PATH'] = "https://www.music-flo.com/api/search/v2/search";

// flo 상세페이지 api 경로
$_SERVER['FLO_API_DETAIL_PATH'] = "https://www.music-flo.com/api/meta/v1/track/";

// 유튜브 뮤직 검색 경로
$_SERVER['YOUTUBE_MUSIC_SEARCH_PATH'] = fn($q) => "https://music.youtube.com/search?q=$q";

// 지니 검색 경로
$_SERVER['GENIE_SEARCH_PATH'] = fn($q) => "https://www.genie.co.kr/search/searchMain?query=$q";

// 지니 앱 url 스키마
$_SERVER['GENIE_APP_PATH'] = 'fb256937297704300://open';

// 플로 상세페이지 경로
$_SERVER['FLO_DETAIL_PATH'] = fn($id) => "https://www.music-flo.com/detail/track/$id/details";

// 플로 앱 상세페이지 url 스키마
$_SERVER['FLO_APP_DETAIL_PATH'] = fn($id) => "flomusic://view/content?type=TRACK&id=$id";


/**
 * redis key
 */
// flo api 조회 결과 캐시 키
$_SERVER['REDIS_API_RESULT_PREFIX'] = "api-result:";

// 아티스트 이미지 캐시 키
$_SERVER['REDIS_ARTIST_IMG_PREFIX'] = "artist-img:";
