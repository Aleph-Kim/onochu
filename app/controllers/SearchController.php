<?

use core\Controller;

/**
 * SearchController 클래스
 *
 * FLO API를 통해 음악 검색 결과를 반환하는 컨트롤러
 */
class SearchController extends Controller
{
    /**
     * 검색어를 받아서 FLO API에 요청하고 노래 정보를 반환
     *
     * XSS 방지를 위해 검색어를 처리 후, API 요청과 JSON 디코딩, 노래 정보 추출
     *
     * @return array 노래 정보 배열
     */
    public function index()
    {
        // XSS 방지 처리
        $_GET['q'] = htmlspecialchars($_GET['q']);

        // 검색어
        $keyword = $_GET['q'];

        // 빈 검색어 처리
        if (empty($keyword)) {
            ErrorHandler::handleError(500);
        }

        // JSON 데이터 가져오기
        $jsonData = $this->fetchFloSongData($keyword);
        if ($jsonData === false) {
            ErrorHandler::handleError(500);
        }

        // JSON 데이터 디코딩
        $data = json_decode($jsonData, true);
        if ($data === null) {
            ErrorHandler::handleError(500);
        }

        // 노래 정보 추출
        $songs = $this->extractSongs($data);

        return $songs;
    }

    /**
     * API 요청 URL을 구성
     *
     * 주어진 검색어로 FLO API 요청 URL 생성
     *
     * @param string $keyword 검색어
     * @return string 구성된 API 요청 URL
     */
    protected function buildUrl($keyword)
    {
        $baseUrl = "https://www.music-flo.com/api/search/v2/search";
        $params = [
            'keyword'    => $keyword,
            'searchType' => 'TRACK',
            'sortType'   => 'ACCURACY',
            'size'       => 10,
            'page'       => 1,
            'queryType'  => 'system'
        ];

        return $baseUrl . '?' . http_build_query($params);
    }

    /**
     * FLO API 호출
     *
     * 주어진 검색어를 사용해서 API 요청 URL을 만들고, 해당 URL로부터 JSON 데이터를 받아옴
     *
     * @param string $keyword 검색어
     * @return string|false API에서 받은 JSON 데이터 문자열 또는 실패 시 false
     */
    protected function fetchFloSongData($keyword)
    {
        // API 요청 URL 구성
        $url = $this->buildUrl($keyword);
        return file_get_contents($url);
    }

    /**
     * API 응답 데이터에서 노래 정보 추출
     *
     * 디코딩된 JSON 데이터를 바탕으로 노래, 아티스트, 앨범 정보를 배열 형태로 반환
     *
     * @param array $data 디코딩된 API 응답 데이터
     * @return array 추출된 노래 정보 배열
     */
    protected function extractSongs($data)
    {
        $songs = [];
        if (!isset($data['data']['list'])) {
            return $songs;
        }

        foreach ($data['data']['list'] as $category) {
            if ($category['type'] === 'TRACK' && isset($category['list'])) {
                foreach ($category['list'] as $track) {
                    $trackData = [];

                    // 노래 정보 처리
                    $trackData['song'] = [
                        'id'        => $track['id'],
                        'name'      => $track['name'],
                        'play_time' => $track['playTime'],
                    ];

                    // 아티스트 정보 처리
                    if (isset($track['artistList'][0])) {
                        $artist = $track['artistList'][0];
                        $trackData['artist'] = [
                            'id'      => $artist['id'],
                            'name'    => $artist['name'],
                            'img_url' => explode('?', $artist['imgList'][0]['url'])[0],
                        ];
                    }

                    // 앨범 정보 처리
                    if (isset($track['album'])) {
                        $album = $track['album'];
                        $trackData['album'] = [
                            'id'      => $album['id'],
                            'title'   => $album['title'],
                            'img_url' => explode('?', $album['imgList'][0]['url'])[0],
                        ];
                    }

                    $songs[] = $trackData;
                }
            }
        }
        return $songs;
    }
}
