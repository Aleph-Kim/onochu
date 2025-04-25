<?
class MainController extends Controller
{
    protected $flo_api;

    protected $recommends_model;

    protected $artists_model;

    protected $new_albums_model;

    public function __construct()
    {
        $this->flo_api = new FloApiHelper();

        $this->recommends_model = $this->model('Recommends');
        $this->artists_model = $this->model('Artists');
        $this->new_albums_model = $this->model('NewAlbums');
    }

    /**
     * 메인 페이지 데이터 조회
     * 
     * @return array 추천곡, 새 앨범, 아티스트 정보를 포함한 배열
     */
    public function index()
    {
        $recommends = $this->recommends_model->getRecentRecommends();
        $artists = UserHelper::checkLogin() ?
            $this->artists_model->getUserArtists() :
            $this->artists_model->getPopularArtists();

        $new_albums = $this->getNewAlbumsForUser($artists);

        return [
            'recommends' => $recommends,
            'artists' => $artists,
            'new_albums' => $new_albums,
        ];
    }

    /**
     * 사용자 맞춤 새 앨범 정보 조회
     * 
     * @param array $artists 사용자 관심 아티스트 또는 인기 아티스트 정보
     * @return array 새 앨범 정보 배열
     */
    private function getNewAlbumsForUser($artists)
    {
        $new_albums = [];
        // 새로나온 앨범 조회
        $new_album_data = $this->new_albums_model->getRecentAlbumsAndArtists();
        // 앨범 정보 구조화
        $new_album_data = $this->structureAlbums($new_album_data);
        // 사용자 관심 아티스트 id 추출
        $artist_flo_ids = array_column($artists, 'flo_id');

        // 새 앨범을 발매한 아티스트와 사용자 관심 아티스트의 교집합 id 추출
        $matching_artist_ids = array_intersect(array_keys($new_album_data['artist_flo_ids']), $artist_flo_ids);

        // 교집합 아티스트 id를 키로 사용하여 새 앨범 정보를 추가
        foreach ($matching_artist_ids as $artist_flo_id) {
            $album_flo_id = $new_album_data['artist_flo_ids'][$artist_flo_id];

            // 이미 추가된 앨범이면 스킵
            if (isset($new_albums[$album_flo_id])) {
                continue;
            }
            $new_albums[$album_flo_id] = $new_album_data['new_albums'][$album_flo_id];
        }

        // 배열 인덱스 재정렬 후 반환
        return array_values($new_albums);
    }

    private function structureAlbums($new_album_data)
    {
        $new_albums = [];
        $artist_flo_ids = [];

        // 결과를 앨범 단위로 구조화
        foreach ($new_album_data as $row) {
            $album_flo_id = $row['album_flo_id'];

            // 앨범 정보가 없으면 추가
            if (!isset($new_albums[$album_flo_id])) {
                $new_albums[$album_flo_id] = [
                    'id' => $row['id'],
                    'title' => $row['album_title'],
                    'img_url' => $row['album_img_url'],
                    'flo_id' => $row['album_flo_id'],
                    'artists' => []
                ];
            }

            // 아티스트 정보가 있으면 추가
            if ($row['artist_name']) {
                $new_albums[$album_flo_id]['artists'][] = [
                    'name' => $row['artist_name'],
                    'flo_id' => $row['artist_flo_id']
                ];
                $artist_flo_ids[$row['artist_flo_id']] = $album_flo_id;
            }
        }

        return [
            'new_albums' => $new_albums,
            'artist_flo_ids' => $artist_flo_ids
        ];
    }
}
