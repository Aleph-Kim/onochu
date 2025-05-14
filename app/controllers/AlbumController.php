<?

/**
 * AlbumController 클래스
 *
 * FLO API를 통해 앨범 관련 정보를 반환하는 컨트롤러
 */
class AlbumController extends Controller
{
    // FloApiHelper 객체 프로퍼티
    protected $flo_api;
    protected $redis;
    protected $artists_model;
    
    // 컨트롤러 클래스가 호출될 때
    public function __construct()
    {
        // FloApiHelper 객체를 인스턴스화하여 할당
        $this->flo_api = new FloApiHelper();
        $this->redis = new Redis();
        $this->artists_model = $this->model('Artists');
    }

    /**
     * 앨범 상세페이지
     */
    public function detail()
    {
        // XSS 방지 처리
        $_GET['id'] = htmlspecialchars($_GET['id']);

        // 앨범 flo_id
        $album_id = $_GET['id'];
        $new_album_bool = isset($_GET['new_album']) && $_GET['new_album'] == 'true';

        // 빈 앨범 id 처리
        if (empty($album_id)) {
            ErrorHandler::showErrorPage(400);
        }

        $album = $this->flo_api->getAlbumByFloId($album_id);
        
        // 새로 나온 앨범일 경우 아티스트 이미지 캐싱 처리
        if ($new_album_bool) {
            $artist_flo_id = $album['songs_info'][0]['artists'][0]['flo_id'];
            $cache_key = $_SERVER['REDIS_ARTIST_IMG_PREFIX'] . $artist_flo_id;
            $artist = $this->artists_model->getByFloId($artist_flo_id);
            $this->redis->set($cache_key, $artist['flo_img_url']);
        }

        return $album;
    }
} 