<?
class RecommendsController extends Controller
{

    // FloApi 객체 프로퍼티
    protected $flo_api;

    // 컨트롤러 클래스가 호출될 때
    public function __construct()
    {
        // FloApi 객체를 인스턴스화하여 할당
        $this->flo_api = new FloApi();

        // model 인스턴스화
        $this->artists_model = $this->model('Artists');
        $this->albums_model = $this->model('Albums');
        $this->songs_model = $this->model('Songs');
        $this->recommends_model = $this->model('Recommends');
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

    public function post()
    {
        // 가수 저장
        $artist = $_SESSION['songInfo']['artist'];
        $artist['id'] = $this->artists_model->insert($artist);

        // 앨범 저장
        $album = $_SESSION['songInfo']['album'];
        $album['artist_id'] = $artist['id'];
        $album['id'] = $this->albums_model->insert($album);

        // 노래 저장
        $song = $_SESSION['songInfo']['song'];
        $song['album_id'] = $album['id'];
        $song['artist_id'] = $album['artist_id'];
        $song['id'] = $this->songs_model->insert($song);

        // 추천 저장
        $recommends = $_POST;
        $recommends['user_id'] = 1; // 임시 유저 id 하드코딩
        $recommends['song_id'] = 1;
        $recommends['score'] = 1;
        $this->recommends_model->insert($recommends);
    }
}
