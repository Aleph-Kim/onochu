<?

class AuthController extends Controller
{
    private $clientId;
    private $clientSecret;
    private $redirectUri;
    private $kakaoApiUrl = 'https://kauth.kakao.com';
    private $kakaoApiUrlV2 = 'https://kapi.kakao.com';

    private $user_model;

    public function __construct()
    {
        $this->clientId = getenv('KAKAO_CLIENT_ID');
        $this->clientSecret = getenv('KAKAO_CLIENT_SECRET');
        // 현재 페이지의 프로토콜과 호스트를 사용하여 리다이렉트 URI 생성
        $this->redirectUri = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . getenv('KAKAO_REDIRECT_URI');

        $this->user_model = $this->model('User');
    }

    /**
     * 카카오 로그인 페이지로 리다이렉트
     */
    public function login()
    {
        // 로그인 상태 확인
        if (isset($_SESSION['user'])) {
            header('Location: /');
            exit;
        }

        $params = [
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'response_type' => 'code',
            'scope' => 'profile_nickname'
        ];

        $authUrl = $this->kakaoApiUrl . '/oauth/authorize?' . http_build_query($params);
        header('Location: ' . $authUrl);
        exit;
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
        $userInfo = $this->getUserInfo($token);

        // 사용자 정보 조회
        $user = $this->user_model->getUserByKakaoId($userInfo['id']);

        // 사용자 정보가 없으면 생성
        if (!$user) {
            $this->user_model->createUser($userInfo);
        }

        // 사용자 정보를 세션에 저장
        $_SESSION['user'] = [
            'id' => $user['id'],
            'nickname' => $user['nickname'],
        ];

        // 로그인 성공 후 메인 페이지로 리다이렉트
        header('Location: /');
        exit;
    }

    /**
     * 액세스 토큰 발급
     */
    private function getAccessToken($code)
    {
        $params = [
            'grant_type' => 'authorization_code',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri' => $this->redirectUri,
            'code' => $code
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->kakaoApiUrl . '/oauth/token');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
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
        curl_setopt($ch, CURLOPT_URL, $this->kakaoApiUrlV2 . '/v2/user/me');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/x-www-form-urlencoded'
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            throw new Exception('사용자 정보 조회 실패');
        }

        return json_decode($response, true);
    }

    /**
     * 카카오 로그아웃
     */
    public function logout()
    {
        if (isset($_SESSION['user'])) {
            unset($_SESSION['user']);
        }
        header('Location: /');
        exit;
    }
}
