<?

class AuthController extends Controller
{
    private $client_id;
    private $client_secret;
    private $redirect_uri;
    private $kakao_api_url = 'https://kauth.kakao.com';
    private $kakao_api_url_v2 = 'https://kapi.kakao.com';

    private $user_model;

    public function __construct()
    {
        $this->client_id = getenv('KAKAO_CLIENT_ID');
        $this->client_secret = getenv('KAKAO_CLIENT_SECRET');
        // 현재 페이지의 프로토콜과 호스트를 사용하여 리다이렉트 URI 생성
        $this->redirect_uri = (UserHelper::isHttps() ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . getenv('KAKAO_REDIRECT_URI');

        $this->user_model = $this->model('User');
    }

    /**
     * 카카오 로그인 페이지로 리다이렉트
     */
    public function login()
    {
        // 로그인 상태 확인
        if (UserHelper::checkLogin()) {
            ScriptHelper::go("/");
        }

        $params = [
            'client_id' => $this->client_id,
            'redirect_uri' => $this->redirect_uri,
            'response_type' => 'code',
            'scope' => 'profile_nickname'
        ];

        $auth_url = $this->kakao_api_url . '/oauth/authorize?' . http_build_query($params);
        ScriptHelper::go($auth_url);
    }

    /**
     * 카카오 로그인 콜백 처리
     */
    public function callback()
    {
        if (!isset($_GET['code'])) {
            throw new Exception('인증 코드가 없음');
        }

        // 액세스 토큰 받기
        $token = $this->getAccessToken($_GET['code']);

        // 사용자 정보 받기
        $user_info = $this->getUserInfo($token);

        // 사용자 정보 조회
        $user = $this->user_model->getUserByKakaoId($user_info['id']);

        // 사용자 정보가 없으면 생성
        if (!$user) {
            $user = $this->user_model->createUser($user_info);
        }

        // 사용자 정보를 세션에 저장
        $_SESSION['user'] = [
            'id' => $user['id'],
            'nickname' => $user['nickname'],
        ];

        // 로그인 전 마지막 페이지로 리다이렉트
        if (isset($_COOKIE['last_url'])) {
            UserHelper::sendLastUrl();
        } else {
            ScriptHelper::go("/");
        }
    }

    /**
     * 액세스 토큰 발급
     */
    private function getAccessToken($code)
    {
        $params = [
            'grant_type' => 'authorization_code',
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'redirect_uri' => $this->redirect_uri,
            'code' => $code
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->kakao_api_url . '/oauth/token');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code !== 200) {
            throw new Exception('액세스 토큰 발급 실패');
        }

        $result = json_decode($response, true);
        return $result['access_token'];
    }

    /**
     * 사용자 정보 조회
     */
    private function getUserInfo($accessToken)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->kakao_api_url_v2 . '/v2/user/me');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/x-www-form-urlencoded'
        ]);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code !== 200) {
            throw new Exception('사용자 정보 조회 실패');
        }

        return json_decode($response, true);
    }

    /**
     * 카카오 로그아웃
     */
    public function logout()
    {
        if (UserHelper::checkLogin()) {
            unset($_SESSION['user']);
        }

        ScriptHelper::go("/");
    }
}
