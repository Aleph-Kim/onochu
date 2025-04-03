<?
class MainController extends Controller
{
    protected $flo_api;

    protected $recommends_model;

    protected $artists_model;

    public function __construct()
    {
        $this->flo_api = new FloApiHelper();

        $this->recommends_model = $this->model('Recommends');
        $this->artists_model = $this->model('Artists');
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
        $new_album_data = $this->getNewAlbumData();

        // 사용자 관심 아티스트 id 추출
        $artists_flo_id = array_column($artists, 'flo_id');

        // 새 앨범을 발매한 아티스트와 사용자 관심 아티스트의 교집합 id 추출
        $matching_artist_ids = array_intersect(array_keys($new_album_data['artists_flo_id']), $artists_flo_id);

        // 교집합 아티스트 id를 키로 사용하여 새 앨범 정보를 추가
        foreach ($matching_artist_ids as $artist_id) {
            $new_albums[] = $new_album_data['albums_info'][$new_album_data['artists_flo_id'][$artist_id]];
        }

        return $new_albums;
    }

    /**
     * 새 앨범 데이터 조회
     * 
     * @return array 앨범 정보와 아티스트 id를 포함한 배열
     */
    private function getNewAlbumData()
    {
        $kpop_data = $this->flo_api->getNewKpopAlbum();
        $pop_data = $this->flo_api->getNewPopAlbum();

        return [
            'albums_info' => $kpop_data['albums_info'] + $pop_data['albums_info'],
            'artists_flo_id' => $kpop_data['artists_flo_id'] + $pop_data['artists_flo_id']
        ];
    }
}
