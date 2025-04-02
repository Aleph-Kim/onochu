<?
class RecommendsController extends Controller
{

    // FloApiHelper 객체 프로퍼티
    protected $flo_api;

    // model 프로퍼티
    protected $artists_model;
    protected $albums_model;
    protected $songs_model;
    protected $recommends_model;

    // 컨트롤러 클래스가 호출될 때
    public function __construct()
    {
        // FloApiHelper 객체를 인스턴스화하여 할당
        $this->flo_api = new FloApiHelper();

        // model 인스턴스화
        $this->artists_model = $this->model('Artists');
        $this->albums_model = $this->model('Albums');
        $this->songs_model = $this->model('Songs');
        $this->recommends_model = $this->model('Recommends');
    }

    public function index()
    {
        if (!UserHelper::checkLogin()) {
            ScriptHelper::msgGo("로그인 후 이용해주세요.", "/login");
        }

        // XSS 방지 처리
        $_GET['id'] = htmlspecialchars($_GET['id']);

        // 검색어
        $song_id = $_GET['id'];

        // 빈 검색어 처리
        if (empty($song_id)) {
            ErrorHandler::showErrorPage(400);
        }

        return $this->flo_api->getSongByFloId($song_id);
    }

    public function post()
    {
        if (!UserHelper::checkLogin()) {
            ScriptHelper::msgGo("로그인 후 이용해주세요.", "/login");
        }

        $song_info = $_SESSION['song_info'];

        // 가수 조회 및 저장
        $artists = [];
        foreach ($song_info['artists'] as $artist_info) {
            $artist = $this->artists_model->getByFloId($artist_info['flo_id']) ?: $artist_info;
            if ($artist['id'] == null) {
                // 이미지 서버에 업로드
                $upload_img_url = $artist['img_url'] . $_SERVER['FLO_IMG_RESIZE_PATH']($_SERVER['IMG_FULL_SIZE']);
                $artist['img_url'] = ImageHelper::uploadImage($upload_img_url, 'artist');
                // 가수 저장
                $artist['id'] = $this->artists_model->insert($artist);
            }
            $artists[] = $artist;
        }

        // 앨범 조회
        $album = $this->albums_model->getByFloId($song_info['album']['flo_id']) ?: $song_info['album'];
        if ($album['id'] == null) {
            $upload_img_url = $album['img_url'] . $_SERVER['FLO_IMG_RESIZE_PATH']($_SERVER['IMG_FULL_SIZE']);
            // 이미지 서버에 업로드
            $album['img_url'] = ImageHelper::uploadImage($upload_img_url);
            // 앨범 저장
            $album['id'] = $this->albums_model->insert($album);
        }

        // 노래 조회
        $song = $this->songs_model->getByFloId($song_info['song']['flo_id']) ?: $song_info['song'];
        if ($song['id'] == null) {
            $song['album_id'] = $album['id'];
            // 노래와 아티스트 관계 저장
            $song['id'] = $this->songs_model->insert($song);
            foreach ($artists as $artist) {
                $this->songs_model->insertSongArtistRelation($song['id'], $artist['id']);
            }
        }

        // 추천 저장
        $recommends = $_POST;
        $recommends['user_id'] = $_SESSION['user']['id'];
        $recommends['song_id'] = $song['id'];
        $recommends['id'] = $this->recommends_model->insert($recommends);

        ScriptHelper::msgGo("추천이 저장되었습니다.", "/recommends/detail?id=" . $recommends['id']);
    }

    /**
     * 추천 상세 조회
     */
    public function detail()
    {
        // XSS 방지 처리
        $_GET['id'] = htmlspecialchars($_GET['id']);

        // 검색어
        $recommend_id = $_GET['id'];

        // 빈 검색어 처리
        if (empty($recommend_id)) {
            ErrorHandler::showErrorPage(400);
        }

        return $this->recommends_model->getById($recommend_id);
    }
}
