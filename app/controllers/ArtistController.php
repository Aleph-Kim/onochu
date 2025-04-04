<?

/**
 * ArtistController 클래스
 *
 * FLO API를 통해 아티스트 관련 정보를 반환하는 컨트롤러
 */
class ArtistController extends Controller
{
    // FloApiHelper 객체 프로퍼티
    protected $flo_api;

    // 컨트롤러 클래스가 호출될 때
    public function __construct()
    {
        // FloApiHelper 객체를 인스턴스화하여 할당
        $this->flo_api = new FloApiHelper();
    }

    /**
     * 아티스트 상세페이지
     */
    public function detail()
    {
        // XSS 방지 처리
        $_GET['id'] = htmlspecialchars($_GET['id']);

        // 아티스트 flo_id
        $artist_id = $_GET['id'];

        // 빈 아티스트 id 처리
        if (empty($artist_id)) {
            ErrorHandler::showErrorPage(400);
        }

        return [
            'artist_info' => $this->flo_api->getArtistByFloId($artist_id),
            'albums_info' => $this->flo_api->getAlbumsByArtistFloId($artist_id)
        ];
    }
} 