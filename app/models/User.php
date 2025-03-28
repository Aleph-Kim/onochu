<?

class User extends Model
{
    public function getAllUsers()
    {
        $stmt = $this->db->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserByKakaoId($kakaoId)
    {
        $sql = "
            SELECT * 
            FROM users 
            WHERE kakao_id = :kakaoId
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['kakaoId' => $kakaoId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($userInfo)
    {
        $sql = "
            INSERT INTO users (kakao_id, nickname) 
            VALUES (:kakaoId, :nickname)
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['kakaoId' => $userInfo['id'], 'nickname' => $userInfo['properties']['nickname']]);
    }
}
