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
        $params = [
            'keyword'    => $keyword,
            'searchType' => 'TRACK',
            'sortType'   => 'ACCURACY',
            'size'       => 30,
            'page'       => 1,
            'queryType'  => 'system'
        ];

        return $this->fetchAndExtract($_SERVER['FLO_API_SEARCH_PATH'], $params, 'getSongs');
    }

    /**
     * 단일 노래 조회 메서드
     * @param string $song_id flo 노래 id
     * @return array 노래 정보 배열
     */
    public function getSongByFloId($song_id)
    {
        return $this->fetchAndExtract($_SERVER['FLO_API_DETAIL_PATH'] . $song_id, null, 'getSong');
    }

    /**
     * 아티스트의 정보를 가져오는 메서드
     * @param string $artist_id FLO 아티스트 ID
     * @return array 아티스트 정보 배열
     */
    public function getArtistByFloId($artist_id)
    {
        return $this->fetchAndExtract($_SERVER['FLO_API_ARTIST_PATH']($artist_id), null, 'getArtist');
    }

    /**
     * 아티스트의 앨범 목록을 가져오는 메서드
     * @param string $artist_id FLO 아티스트 ID
     * @return array 아티스트의 앨범 정보 배열
     */
    public function getAlbumsByArtistFloId($artist_id)
    {
        $params = [
            'page' => '1',
            'size' => '100',
            'sortType'  => 'RECENT',
            'roleType'  => 'ALL'
        ];

        return $this->fetchAndExtract($_SERVER['FLO_API_ALBUMS_PATH']($artist_id), $params, 'getAlbums', $artist_id);
    }

    /**
     * 앨범의 정보를 가져오는 메서드
     * @param string $album_id FLO 앨범 ID
     * @return array 앨범 정보 배열
     */
    public function getAlbumByFloId($album_id)
    {
        return $this->fetchAndExtract($_SERVER['FLO_API_ALBUM_PATH']($album_id), null, 'getAlbum');
    }

    /**
     * 새로 발매된 케이팝 앨범 정보를 가져오는 메서드
     * @return array 새로 발매된 케이팝 앨범 정보 배열
     */
    public function getNewKpopAlbum()
    {
        $params = [
            'page' => '1',
            'size' => '100',
        ];
        return $this->fetchAndExtract($_SERVER['FLO_API_NEW_KPOP_ALBUM_PATH'], $params, 'getNewAlbums');
    }

    /**
     * 새로 발매된 팝 앨범 정보를 가져오는 메서드
     * @return array 새로 발매된 팝 앨범 정보 배열
     */
    public function getNewPopAlbum()
    {
        $params = [
            'page' => '1',
            'size' => '100',
        ];
        return $this->fetchAndExtract($_SERVER['FLO_API_NEW_POP_ALBUM_PATH'], $params, 'getNewAlbums');
    }

    /**
     * API에서 데이터를 가져오고 추출하는 메서드
     * @param string $path API 엔드포인트 경로
     * @param array|null $params 요청 파라미터
     * @param string $getMethod 데이터를 가져오는 메서드 이름
     * @param string|null $other_params 추가 파라미터
     * @return array 추출한 데이터
     */
    protected function fetchAndExtract($path, $params = null, $getMethod, $other_params = null)
    {
        $full_path = $path . ($params ? '?' . http_build_query($params) : "");
        $data = $this->fetchData($full_path);
        return $this->$getMethod($data, $other_params);
    }

    /**
     * API에서 데이터를 가져오는 메서드
     * @param string $path API 엔드포인트 경로
     * @return array 디코딩된 JSON 데이터
     */
    protected function fetchData($path)
    {
        // 캐시 키 생성
        $cache_key = $_SERVER['REDIS_API_RESULT_PREFIX'] . $path;
        // 캐시 데이터 확인
        $cache_data = $this->redis->get($cache_key);
        if ($cache_data) {
            return $cache_data;
        }

        // 요청 URL 생성
        $url = $_SERVER['FLO_API_PATH'] . $path;
        // JSON 데이터 가져오기
        $json_data = @file_get_contents($url);
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
     * 노래 조회 API 응답에서 노래 정보를 추출하는 메서드
     * @param array $data API 응답 데이터
     * @return array 추출한 노래 정보 배열
     */
    protected function getSong($data)
    {
        if (!isset($data['data'])) {
            ErrorHandler::showErrorPage(400);
        }

        return $this->extractTrack($data['data']);
    }

    /**
     * 검색 API 응답에서 노래 정보를 추출하는 메서드
     * @param array $data API 응답 데이터
     * @return array 추출한 노래 정보 배열
     */
    protected function getSongs($data)
    {
        $songs = [];
        if (!isset($data['data']) || !is_array($data['data']['list'] ?? [])) {
            return $songs;
        }

        $track = $data['data']['list'][0];
        if (!isset($track['list'])) {
            return $songs;
        }

        foreach ($track['list'] as $song_data) {
            $songs[] = $this->extractTrack($song_data);
        }

        return $songs;
    }

    /**
     * 앨범 API 응답에서 앨범 정보를 추출하는 메서드
     * @param array $data API 응답 데이터
     * @return array 추출한 앨범 정보 배열
     */
    protected function getAlbum($data)
    {
        if (!isset($data['data'])) {
            ErrorHandler::showErrorPage(400);
        }

        $result = [
            'album_info' => [],
            'songs_info' => []
        ];

        if (!isset($data['data']) || !is_array($data['data']['list'] ?? [])) {
            return $result;
        }

        $album_data = $data['data']['list'][0]['album'];
        $result['album_info'] = $this->extractAlbum($album_data);

        foreach ($data['data']['list'] as $song_data) {
            $result['songs_info'][] = $this->extractTrack($song_data);
        }

        return $result;
    }

    /**
     * 앨범 API 응답에서 앨범 정보를 추출하는 메서드
     * @param array $data API 응답 데이터
     * @return array 추출한 앨범 정보 배열
     */
    protected function getAlbums($data, $artist_id)
    {
        $albums_info = [];
        if (!isset($data['data']) || !is_array($data['data']['list'] ?? [])) {
            return $albums_info;
        }

        foreach ($data['data']['list'] as $album) {
            $album_info = $this->extractAlbum($album);
            $album_info['artists'] = $this->extractArtists($album['artistList']);

            if (count($album_info['artists']) > 1 || $artist_id != $album_info['artists'][0]['flo_id']) {
                $album_info['type'] = '참여';
            }

            $albums_info[] = $album_info;
        }

        return $albums_info;
    }

    /**
     * 새로 발매된 앨범 정보를 가져오는 메서드
     * @param array $data API 응답 데이터
     * @return array {
     *   albums_info: array {
     *     [album_flo_id]: {
     *       flo_id: string,
     *       title: string,
     *       type: string,
     *       genre: string,
     *       img_url: string,
     *       release_date: string,
     *       artist: array[] {
     *         flo_id: string,
     *         name: string,
     *         genre: string,
     *         group_type: string,
     *         img_url: string
     *       }
     *     }
     *   },
     *   artists_flo_id: array {
     *     [artist_flo_id]: album_flo_id
     *   }
     */
    protected function getNewAlbums($data)
    {
        $albums_info = [];
        $artists_flo_id = [];
        if (!isset($data['data']) || !is_array($data['data']['list'] ?? [])) {
            return $albums_info;
        }

        foreach ($data['data']['list'] as $album) {
            $albums_info[$album['id']] = $this->extractAlbum($album);
            $albums_info[$album['id']]['artist'] = $this->extractArtists($album['artistList']);

            foreach ($albums_info[$album['id']]['artist'] as $artist) {
                $artists_flo_id[$artist['flo_id']] = $album['id'];
            }
        }

        return [
            'albums_info' => $albums_info,
            'artists_flo_id' => $artists_flo_id
        ];
    }

    /**
     * 아티스트 API 응답에서 아티스트 정보를 추출하는 메서드
     * @param array $data API 응답 데이터
     * @return array 추출한 아티스트 정보 배열
     */
    protected function getArtist($data)
    {
        if (!isset($data['data'])) {
            ErrorHandler::showErrorPage(400);
        }

        return $this->extractArtist($data['data']);
    }

    /**
     * 데이터에서 트랙 정보를 추출하는 메서드
     * @param array $song_data 노래 데이터
     * @return array 추출한 노래 정보 배열
     */
    protected function extractTrack($song_data)
    {
        $song_info = [];
        $song_info['song'] = $this->extractSong($song_data);
        $song_info['artists'] = $this->extractArtists($song_data['artistList'] ?? []);
        $song_info['album'] = $this->extractAlbum($song_data['album'] ?? []);
        $song_info['song']['url'] = PlatformHelper::getPlatformUrl($song_info['song'], $song_info['artists']);

        return $song_info;
    }

    /**
     * 데이터에서 노래 정보를 추출하는 메서드
     * @param array $song_data 노래 데이터
     * @return array 추출한 노래 정보
     */
    protected function extractSong($song_data): array
    {
        $writerRoles = $this->writerClassify($song_data['trackArtistList'] ?? []);

        return [
            'flo_id' => $song_data['id'],
            'title' => $song_data['name'],
            'play_time' => $song_data['playTime'],
            'genre' => $song_data['album']['genreStyle'] ?? null,
            'title_yn' => $song_data['titleYn'] ?? null,
            'lyrics' => $song_data['lyrics'] ?? null,
            'composer' => implode(', ', $writerRoles['composers']),
            'lyricist' => implode(', ', $writerRoles['lyricists']),
            'arranger' => implode(', ', $writerRoles['arrangers']),
        ];
    }

    /**
     * 데이터에서 앨범 정보를 추출하는 메서드
     * @param array $album 앨범 데이터
     * @return array 추출한 앨범 정보
     */
    protected function extractAlbum($album)
    {
        return [
            'flo_id' => $album['id'],
            'title' => $album['title'],
            'type' => $album['albumTypeStr'],
            'genre' => $album['genreStyle'],
            'img_url' => strtok($album['imgList'][0]['url'] ?? '', '?'),
            'release_date' => $album['releaseYmd'] ? DateTime::createFromFormat('Ymd', $album['releaseYmd'])->format("Y.m.d") : null
        ];
    }

    /**
     * 데이터에서 아티스트 정보를 추출하는 메서드
     * @param array $artistList 아티스트 리스트
     * @return array 추출한 아티스트 정보 배열
     */
    protected function extractArtists($artistList)
    {
        $artists = [];

        if (!isset($artistList) || !is_array($artistList)) {
            return $artists;
        }

        foreach ($artistList as $artist) {
            $artists[] = $this->extractArtist($artist);
        }

        return $artists;
    }

    /**
     * 데이터에서 아티스트 정보를 추출하는 메서드
     * @param array $artist 아티스트 데이터
     * @return array 추출한 아티스트 정보
     */
    protected function extractArtist($artist)
    {
        if (!isset($artist)) {
            return [];
        }

        // 캐시 키 생성
        $cache_key = $_SERVER['REDIS_ARTIST_IMG_PREFIX'] . $artist['id'];
        // 캐시 데이터 확인
        if ($artist['imgList'][0]['url']) {
            $this->redis->set($cache_key, $artist['imgList'][0]['url']);
        } else {
            $artist['imgList'][0]['url'] = $this->redis->get($cache_key);
        }

        return [
            'flo_id' => $artist['id'],
            'name' => $artist['name'],
            'genre' => $artist['artistStyle'],
            'group_type' => $artist['artistGroupTypeStr'],
            'img_url' => strtok($artist['imgList'][0]['url'] ?? '', '?')
        ];
    }

    /**
     * 작곡가, 작사가, 편곡자를 분류하는 함수
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
}
