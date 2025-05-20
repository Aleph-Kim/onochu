<?
class MypageController extends Controller
{
    public $user_model;
    public $recommends_model;

    public function __construct()
    {
        $this->user_model = $this->model('User');
        $this->recommends_model = $this->model('Recommends');
    }

    public function index()
    {
        if (!UserHelper::checkLogin()) {
            UserHelper::sendLogin();
        }
        $user_id = $_SESSION['user']['id'];

        return $this->getMypageInfo($user_id);
    }

    public function user()
    {
        $user_id = $_GET['id'];

        if (!$user_id) {
            ScriptHelper::msgGo("잘못된 요청입니다.");
        }

        if ($user_id == $_SESSION['user']['id']) {
            ScriptHelper::go("/mypage");
        }

        return $this->getMypageInfo($user_id);
    }

    public function setProfileAlbum()
    {
        if (!UserHelper::checkLogin()) {
            return [
                'code' => 401,
                'message' => '로그인이 필요합니다.'
            ];
        }

        $recommend_id = $_POST['recommend_id'];

        $recommend_info = $this->recommends_model->getByMyRecommend($recommend_id);

        if (!$recommend_info) {
            return [
                'code' => 400,
                'message' => '잘못된 요청입니다.'
            ];
        }

        $this->user_model->setProfileAlbum($recommend_info['album_id']);

        return [
            'message' => '앨범 설정 완료',
            'album_img_url' => $recommend_info['img_url'] . $_SERVER['IMG_RESIZE_PATH']($_SERVER['IMG_FULL_SIZE']),
            'album_flo_id' => $recommend_info['flo_id'],
        ];
    }

    private function getMypageInfo($user_id){
        $user_info = $this->getUserInfo($user_id);
        $artist_list = $this->user_model->getUserLikeArtist($user_info['id']);
        $genre_list = $this->user_model->getUserLikeGenre($user_info['id']);
        $song_list = $this->recommends_model->getRecentRecommends(1000, $user_id);

        return [
            'user_info' => $user_info,
            'artist_list' => $artist_list,
            'genre_list' => $genre_list,
            'song_list' => $song_list
        ];
    }

    private function getUserInfo($user_id)
    {
        $user_info = $this->user_model->getUserInfoById($user_id);

        if (!$user_info) {
            ScriptHelper::msgGo("존재하지 않는 유저입니다.");
        }

        return $user_info;
    }
}
