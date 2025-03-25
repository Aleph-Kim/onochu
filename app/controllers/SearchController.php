<?

/**
 * SearchController 클래스
 *
 * FLO API를 통해 음악 검색 결과를 반환하는 컨트롤러
 */
class SearchController extends Controller
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
            ErrorHandler::showErrorPage(400);
        }

        return $this->flo_api->getSongsByKeyword($keyword);
    }

    /**
     * 노래 상세페이지
     */
    public function songDetail()
    {
        // XSS 방지 처리
        $_GET['id'] = htmlspecialchars($_GET['id']);

        // 노래 flo_id
        $song_id = $_GET['id'];

        // 빈 노래 id 처리
        if (empty($song_id)) {
            ErrorHandler::showErrorPage(400);
        }

        return $this->flo_api->getSongByFloId($song_id);
    }

    /**
     * 아티스트 상세페이지
     */
    public function artistDetail()
    {
        // XSS 방지 처리
        $_GET['id'] = htmlspecialchars($_GET['id']);

        // 노래 flo_id
        $artist_id = $_GET['id'];

        // 빈 노래 id 처리
        if (empty($artist_id)) {
            ErrorHandler::showErrorPage(400);
        }

        return [
            'artist_info' => $this->flo_api->getArtistByFloId($artist_id),
            'albums_info' => $this->flo_api->getAlbumsByArtistFloId($artist_id)
        ];
    }

    /**
     * 앨범 상세페이지
     */
    public function albumDetail()
    {
        // XSS 방지 처리
        $_GET['id'] = htmlspecialchars($_GET['id']);

        // 노래 flo_id
        $album_id = $_GET['id'];

        // 빈 노래 id 처리
        if (empty($album_id)) {
            ErrorHandler::showErrorPage(400);
        }

        return $this->flo_api->getAlbumByFloId($album_id);
    }
}
