<?

$dbHost = getenv("DB_HOST"); // 호스트 주소
$dbName = getenv("DB_NAME"); // 데이타 베이스(DataBase) 이름
$dbUser = getenv("DB_USER"); // DB 아이디
$dbPass = getenv("DB_PASS"); // DB 패스워드
$dbChar = getenv("DB_CHAR"); // 문자 인코딩

class Model
{
    protected $db;

    public function __construct()
    {
        global $dbHost, $dbName, $dbUser, $dbPass, $dbChar;

        $this->db = new PDO("mysql:host={$dbHost};dbname={$dbName};charset={$dbChar}", $dbUser, $dbPass);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}
