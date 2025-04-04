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

    // 컨트롤러 클래스가 호출될 때
    public function __construct()
    {
        // FloApiHelper 객체를 인스턴스화하여 할당
        $this->flo_api = new FloApiHelper();
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

        // 빈 앨범 id 처리
        if (empty($album_id)) {
            ErrorHandler::showErrorPage(400);
        }

        return $this->flo_api->getAlbumByFloId($album_id);
    }
} 