<?

namespace Helpers;

class FloApi
{
    // FLO API 기본 url
    protected $base_url = "https://www.music-flo.com/api";

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
        $jsonData = @file_get_contents($url); // 경고 억제를 위해 @ 사용
        if ($jsonData === false) {
            ErrorHandler::handleError(500);
        }

        // JSON 데이터 디코딩
        $data = json_decode($jsonData, true);
        if ($data === null) {
            ErrorHandler::handleError(500);
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
            ErrorHandler::handleError(400);
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
        $song = [];

        // 노래 정보 처리
        $song['song'] = [
            'id' => $song_data['id'],
            'name' => $song_data['name'],
            'play_time' => $song_data['playTime'],
            'genre' => $song_data['album']['genreStyle'],
        ];

        // 아티스트 정보 처리
        if (isset($song_data['artistList'][0])) {
            $artist = $song_data['artistList'][0];
            $song['artist'] = [
                'id'      => $artist['id'],
                'name'    => $artist['name'],
                'img_url' => strtok($artist['imgList'][0]['url'], '?'),
            ];
        }

        // 앨범 정보 처리
        if (isset($song_data['album'])) {
            $album = $song_data['album'];
            $song['album'] = [
                'id'      => $album['id'],
                'title'   => $album['title'],
                'img_url' => strtok($album['imgList'][0]['url'], '?'),
                'release_date' => \DateTime::createFromFormat('Ymd', $album['releaseYmd'])->format("Y.m.d")
            ];
        }

        return $song;
    }
}
