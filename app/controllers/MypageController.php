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
        $user_info = $this->getUserInfo();
        $artist_list = $this->user_model->getUserLikeArtist($user_info['id']);
        $genre_list = $this->user_model->getUserLikeGenre($user_info['id']);
        $song_list = $this->recommends_model->getRecentRecommends(1000);

        return [
            'user_info' => $user_info,
            'artist_list' => $artist_list,
            'genre_list' => $genre_list,
            'song_list' => $song_list
        ];
    }

    private function getUserInfo()
    {
        if (isset($_GET['id'])) {
            $user_id = (int)$_GET['id'];
        } else {
            if (!UserHelper::checkLogin()) {
                UserHelper::sendLogin();
            }
            $user_id = $_SESSION['user']['id'];
        }

        $user_info = $this->user_model->getUserInfoById($user_id);

        if (!$user_info) {
            ScriptHelper::msgGo("존재하지 않는 유저입니다.");
        }

        return $user_info;
    }
}
