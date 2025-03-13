<?

use Core\Controller;
use Helpers\FloApi;

/**
 * SearchController 클래스
 *
 * FLO API를 통해 음악 검색 결과를 반환하는 컨트롤러
 */
class SearchController extends Controller
{
    // FloApi 객체 프로퍼티
    protected $flo_api;

    // 컨트롤러 클래스가 호출될 때
    public function __construct()
    {
        // FloApi 객체를 인스턴스화하여 할당
        $this->flo_api = new FloApi();
    }

    /**
     * 검색 요청을 처리하는 메인 메서드
     */
    public function index()
    {
        // XSS 방지 처리
        $_GET['q'] = htmlspecialchars($_GET['q']);

        // 검색어
        $keyword = $_GET['q'];

        // 빈 검색어 처리
        if (empty($keyword)) {
            ErrorHandler::handleError(500);
        }

        return $this->flo_api->getSongsByKeyword($keyword);
    }
}
