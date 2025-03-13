<?

use Core\Controller;
use Helpers\FloApi;

class RecommendsController extends Controller
{

    // FloApi 객체 프로퍼티
    protected $flo_api;

    // 컨트롤러 클래스가 호출될 때
    public function __construct()
    {
        // FloApi 객체를 인스턴스화하여 할당
        $this->flo_api = new FloApi();
    }

    public function index()
    {
        // XSS 방지 처리
        $_GET['id'] = htmlspecialchars($_GET['id']);

        // 검색어
        $song_id = $_GET['id'];

        // 빈 검색어 처리
        if (empty($song_id)) {
            ErrorHandler::handleError(400);
        }

        return $this->flo_api->getSongByFloId($song_id);
    }
}
