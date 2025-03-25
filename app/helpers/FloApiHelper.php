<?
class FloApiHelper
{
    protected $redis;

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
        // 검색 파라미터
        $params = [
            'keyword'    => $keyword,
            'searchType' => 'TRACK',
            'sortType'   => 'ACCURACY',
            'size'       => 30,
            'page'       => 1,
            'queryType'  => 'system'
        ];

        // 전체 경로
        $full_path = $_SERVER['FLO_API_SEARCH_PATH'] . ($params ? '?' . http_build_query($params) : "");

        // API 데이터 가져오기
        $data = $this->fetchData($full_path);

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
        // 전체 경로
        $full_path = $_SERVER['FLO_API_DETAIL_PATH'] . $song_id;

        // API 데이터 가져오기
        $data = $this->fetchData($full_path);

        // 검색 결과 추출 및 반환
        return $this->extractGetSong($data);
    }

    /**
     * 아티스트의 앨범 목록을 가져오는 메서드
     * @param string $artist_id FLO 아티스트 ID
     * @return array 아티스트의 앨범 정보 배열
     */
    public function getArtistByFloId($artist_id)
    {
        // 전체 경로
        $path = $_SERVER['FLO_API_ARTIST_PATH']($artist_id);

        // API 데이터 가져오기
        $data = $this->fetchData($path);

        // 앨범 정보 추출 및 반환
        return $this->extractArtist($data);
    }

    /**
     * 아티스트의 앨범 목록을 가져오는 메서드
     * @param string $artist_id FLO 아티스트 ID
     * @return array 아티스트의 앨범 정보 배열
     */
    public function getAlbumsByArtistFloId($artist_id)
    {
        // 검색 파라미터
        $params = [
            'page' => '1',
            'size' => '100',
            'sortType'  => 'RECENT',
            'roleType'  => 'RELEASE'
        ];

        // 전체 경로
        $path = $_SERVER['FLO_API_ALBUMS_PATH']($artist_id) . http_build_query($params);

        // API 데이터 가져오기
        $data = $this->fetchData($path);

        // 앨범 정보 추출 및 반환
        return $this->extractArtistAlbums($data);
    }

    /**
     * 앨범의 상세정보를 가져오는 메서드
     * @param string $album_id FLO 아티스트 ID
     * @return array 아티스트의 앨범 정보 배열
     */
    public function getAlbumByFloId($album_id)
    {
        // 전체 경로
        $path = $_SERVER['FLO_API_ALBUM_PATH']($album_id);

        // API 데이터 가져오기
        $data = $this->fetchData($path);

        // 앨범 정보 추출 및 반환
        return $this->extractAlbum($data);
    }

    /**
     * API에서 데이터를 가져오는 메서드
     * @param string $path API 엔드포인트 경로
     * @return array 디코딩된 JSON 데이터
     */
    protected function fetchData($path)
    {
        // 캐시 키
        $cache_key = $_SERVER['REDIS_API_RESULT_PREFIX'] . $path;
        // 캐싱 데이터
        $cache_data = $this->redis->get($cache_key);
        // 캐싱된 데이터가 있다면 즉시 리턴
        if ($cache_data) {
            return $cache_data;
        }

        // 요청 URL 생성
        $url = $_SERVER['FLO_API_PATH'] . $path;

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
        // 데이터 캐싱
        $this->redis->set($cache_key, $data, "next-hour");

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
            'title_yn' => $song_data['titleYn'],
            'lyrics' => $song_data['lyrics'],
            'composer' => implode(', ', $writerRoles['composers']),
            'lyricist' => implode(', ', $writerRoles['lyricists']),
            'arranger' => implode(', ', $writerRoles['arrangers']),
        ];

        // 아티스트 정보 처리
        $song_info['artists'] = [];
        if (isset($song_data['artistList']) && is_array($song_data['artistList'])) {
            foreach ($song_data['artistList'] as $artist) {
                $artist_info = [
                    'flo_id' => $artist['id'],
                    'name' => $artist['name'],
                    'img_url' => strtok($artist['imgList'][0]['url'], '?'),
                ];

                $cache_key = $_SERVER['REDIS_ARTIST_IMG_PREFIX'] . $artist['id'];
                if ($artist_info['img_url']) {
                    $this->redis->set($cache_key, $artist_info['img_url']);
                } else {
                    $artist_info['img_url'] = $this->redis->get($cache_key);
                }

                $song_info['artists'][] = $artist_info;
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
     * 아티스트 API 응답에서 필요한 정보를 추출하는 메서드
     * @param array $data API 응답 데이터
     * @return array 추출된 아티스트 정보 배열
     */
    protected function extractArtist($data)
    {
        // 반환할 배열 초기화
        $artist_info = [];

        // 유효하지 않은 데이터면 빈 배열 반환
        if (!isset($data['data'])) {
            return $artist_info;
        }

        $artist_data = $data['data'];

        // 앨범 정보 처리
        $artist_info = [
            'flo_id'       => $artist_data['id'],
            'name'        => $artist_data['name'],
            'genre'   => $artist_data['artistStyle'],
            'group_type'   => $artist_data['artistGroupTypeStr'],
            'img_url'    => strtok($artist_data['imgList'][0]['url'], '?')
        ];

        return $artist_info;
    }

    /**
     * 앨범 API 응답에서 필요한 정보를 추출하는 메서드
     * @param array $data API 응답 데이터
     * @return array 추출된 앨범 정보 배열
     */
    protected function extractArtistAlbums($data)
    {
        // 반환할 배열 초기화
        $albums_info = [];

        // 유효하지 않은 데이터면 빈 배열 반환
        if (!isset($data['data']) || !is_array($data['data']['list'] ?? [])) {
            return $albums_info;
        }

        foreach ($data['data']['list'] as $album_data) {
            // 앨범 정보 처리
            $albums_info[] = [
                'flo_id'       => $album_data['id'],
                'title'        => $album_data['title'],
                'type'   => $album_data['albumTypeStr'], // '싱글', '미니', '정규' 등
                'release_date' => \DateTime::createFromFormat('Ymd', $album_data['releaseYmd'])->format("Y.m.d"),
                'img_url'    => strtok($album_data['imgList'][0]['url'], '?')
            ];
        }

        return $albums_info;
    }

    /**
     * 앨범 API 응답에서 필요한 정보를 추출하는 메서드
     * @param array $data API 응답 데이터
     * @return array 추출된 앨범 정보 배열
     */
    protected function extractAlbum($data)
    {
        // 반환할 배열 초기화
        $result = [
            'album_info' => [],
            'songs_info' => []
        ];

        // 유효하지 않은 데이터면 빈 배열 반환
        if (!isset($data['data']) || !is_array($data['data']['list'] ?? [])) {
            return $result;
        }

        $album_data = $data['data']['list'][0]['album'];

        $result['album_info'] = [
            'flo_id'      => $album_data['id'],
            'title'   => $album_data['title'],
            'genre'   => $album_data['genreStyle'],
            'type'   => $album_data['albumTypeStr'],
            'img_url' => strtok($album_data['imgList'][0]['url'], '?'),
            'release_date' => \DateTime::createFromFormat('Ymd', $album_data['releaseYmd'])->format("Y.m.d")
        ];

        foreach ($data['data']['list'] as $song_data) {
            $result['songs_info'][] = $this->extractSong($song_data);
        }

        return $result;
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

        if (!isset($array)) {
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
        $artists_name = implode(' ', array_column($song_info['artists'], 'name'));
        // 검색 시 키워드
        $keyword = "{$song_info['song']['title']} {$artists_name}";
        // 플로에서 사용하는 노래 pk
        $flo_id = $song_info['song']['flo_id'];

        $youtube = $_SERVER['YOUTUBE_MUSIC_SEARCH_PATH']($keyword);
        $genie = $is_mobile ? $_SERVER['GENIE_APP_PATH'] : $_SERVER['GENIE_SEARCH_PATH']($keyword);
        $flo = $is_mobile ? $_SERVER['FLO_APP_DETAIL_PATH']($flo_id) : $_SERVER['FLO_DETAIL_PATH']($flo_id);

        return [
            'youtube' => $youtube,
            'genie' => $genie,
            'flo' => $flo,
        ];
    }
}
