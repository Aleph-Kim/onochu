<?

$dbHost = "172.17.0.1"; // 호스트 주소
$dbName = "test_db"; // 데이타 베이스(DataBase) 이름
$dbUser = "root"; // DB 아이디
$dbPass = "mysql_dev"; // DB 패스워드
$dbChar = "utf8"; // 문자 인코딩

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
