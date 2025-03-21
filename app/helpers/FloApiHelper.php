<?
class FloApiHelper
{
    protected $redis;
    // FLO API 기본 url
    protected $base_url = "https://www.music-flo.com/api";

    public function __construct()
    {
        $this->redis = new Redis();
    }
    /**
     * 노래 검색 메서드
     * @param string $keyword 검색어
     * @return array 검색된 노래 데이터 배열
     */
    public function getSongsByKeyword($keyword)
    {
        // 검색 api 경로
        $search_path = "/search/v2/search";

        // 검색 파라미터
        $params = [
            'keyword'    => urlencode($keyword),
            'searchType' => 'TRACK',
            'sortType'   => 'ACCURACY',
            'size'       => 10,
            'page'       => 1,
            'queryType'  => 'system'
        ];

        // API 데이터 가져오기
        $data = $this->fetchData($search_path, $params);

        // 검색 결과 추출 및 반환
        return $this->extractSearchSongs($data);
    }

    /**
     * 단일 노래 조회 메서드
     * @param string $song_id flo 노래 id
     * @return array 검색된 노래 데이터
     */
    public function getSongByFloId($song_id)
    {
        // 조회 api 경로
        $get_path = "/meta/v1/track/";

        // API 데이터 가져오기
        $data = $this->fetchData($get_path . $song_id);

        // 검색 결과 추출 및 반환
        return $this->extractGetSong($data);
    }

    /**
     * API에서 데이터를 가져오는 메서드
     * @param string $path API 엔드포인트 경로
     * @param array $params 쿼리 파라미터
     * @return array 디코딩된 JSON 데이터
     */
    protected function fetchData($path, $params = null)
    {
        // 요청 URL 생성
        $url = $this->base_url . $path . ($params ? '?' . http_build_query($params) : "");

        // JSON 데이터 가져오기
        $json_data = @file_get_contents($url); // 경고 억제를 위해 @ 사용
        if ($json_data === false) {
            ErrorHandler::showErrorPage(500);
        }

        // JSON 데이터 디코딩
        $data = json_decode($json_data, true);
        if ($data === null) {
            ErrorHandler::showErrorPage(500);
        }

        return $data;
    }

    /**
     * 검색 API 응답에서 노래 정보를 추출하는 메서드
     * @param array $data API 응답 데이터
     * @return array 추출된 노래 정보 배열
     */
    protected function extractSearchSongs($data)
    {
        // 반환할 배열 초기화
        $songs = [];

        // 유효하지 않은 데이터면 빈 배열 반환
        if (!isset($data['data']) || !is_array($data['data']['list'] ?? [])) {
            return $songs;
        }

        $track = $data['data']['list'][0];

        // 노래 데이터가 없으면 빈 배열 반환
        if (!isset($track['list'])) {
            return $songs;
        }

        foreach ($track['list'] as $song_data) {
            $song = self::extractSong($song_data);

            $songs[] = $song;
        }

        return $songs;
    }

    /**
     * 노래 조회 API 응답에서 노래 정보를 추출하는 메서드
     * @param array $data API 응답 데이터
     * @return array 추출된 노래 정보 배열
     */
    protected function extractGetSong($data)
    {
        if (!isset($data['data'])) {
            ErrorHandler::showErrorPage(400);
        }

        $song_data = $data['data'];

        return self::extractSong($song_data);
    }

    /**
     * flo api에서 가져온 노래 데이터에서 필요한 정보만 추출하여 반환하는 메서드
     * 
     * @param array $song_data API 응답 노래 데이터
     * @return array 추출된 노래 정보 배열
     */
    protected function extractSong($song_data)
    {
        $song_info = [];

        $writerRoles = $this->writerClassify($song_data['trackArtistList']);

        // 노래 정보 처리
        $song_info['song'] = [
            'flo_id' => $song_data['id'],
            'title' => $song_data['name'],
            'play_time' => $song_data['playTime'],
            'genre' => $song_data['album']['genreStyle'],
            'lyrics' => $song_data['lyrics'],
            'composer' => implode($writerRoles['composers'], ', '),
            'lyricist' => implode($writerRoles['lyricists'], ', '),
            'arranger' => implode($writerRoles['arrangers'], ', '),
        ];

        // 아티스트 정보 처리
        if (isset($song_data['artistList'][0])) {
            $artist = $song_data['artistList'][0];
            $song_info['artist'] = [
                'flo_id'      => $artist['id'],
                'name'    => $artist['name'],
                'img_url' => strtok($artist['imgList'][0]['url'], '?'),
            ];

            // 아티스트 이미지가 있다면 캐싱, 없다면 캐싱딘 이미지 사용
            $cache_key = 'artist_img_' . $artist['id'];
            if ($song_info['artist']['img_url']) {
                $this->redis->set($cache_key, $song_info['artist']['img_url']);
            } else {
                $song_info['artist']['img_url'] = $this->redis->get($cache_key, $song_info['artist']['img_url']);
            }
        }

        // 앨범 정보 처리
        if (isset($song_data['album'])) {
            $album = $song_data['album'];
            $song_info['album'] = [
                'flo_id'      => $album['id'],
                'title'   => $album['title'],
                'img_url' => strtok($album['imgList'][0]['url'], '?'),
                'release_date' => \DateTime::createFromFormat('Ymd', $album['releaseYmd'])->format("Y.m.d")
            ];
        }

        $song_info['song']['url'] = $this->getPlatformUrl($song_info);

        return $song_info;
    }

    /**
     * 작곡가, 작사가, 편곡자를 분류하는 함수
     * 
     * @param array $array 입력 배열
     * @return array 각 역할별로 분류된 결과 (composers, lyricists, arrangers)
     */
    protected function writerClassify($array)
    {
        $result = [
            'composers' => [],
            'lyricists' => [],
            'arrangers' => []
        ];

        if (!isset($array)){
            return $result;
        }

        foreach ($array as $item) {
            switch ($item['roleName']) {
                case '작곡':
                    $result['composers'][] = $item['name'];
                    break;
                case '작사':
                    $result['lyricists'][] = $item['name'];
                    break;
                case '편곡':
                    $result['arrangers'][] = $item['name'];
                    break;
            }
        }

        return $result;
    }

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
    protected function getPlatformUrl($song_info)
    {
        // 사용자 접속환경 모바일 여부
        $is_mobile = UserHelper::isMobile();

        $youtube = "https://music.youtube.com/search?q={$song_info['song']['title']}+{$song_info['artist']['name']}";
        $genie = $is_mobile ? "fb256937297704300://open" : "https://www.genie.co.kr/search/searchMain?query={$song_info['song']['title']}+{$song_info['artist']['name']}";
        $flo = $is_mobile ? "flomusic://view/content?type=TRACK&id={$song_info['song']['flo_id']}" : "https://www.music-flo.com/detail/track/{$song_info['song']['flo_id']}/details";

        return [
            'youtube' => $youtube,
            'genie' => $genie,
            'flo' => $flo,
        ];
    }
}
