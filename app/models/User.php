<?

class User extends Model
{
    public function getAllUsers()
    {
        $stmt = $this->db->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserByKakaoId($kakao_id)
    {
        $sql = "
            SELECT * 
            FROM users 
            WHERE kakao_id = :kakao_id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['kakao_id' => $kakao_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($user_info)
    {
        $sql = "
            INSERT INTO users (kakao_id, nickname) 
            VALUES (:kakao_id, :nickname)
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['kakao_id' => $user_info['id'], 'nickname' => $user_info['properties']['nickname']]);

        return [
            'id' => $this->db->lastInsertId(),
            'nickname' => $user_info['properties']['nickname']
        ];
    }

    public function getUserInfoById($user_id)
    {
        $sql = "
            SELECT u.id, u.nickname, COUNT(r.id) as recommend_count, a.flo_id as profile_album_flo_id, a.img_url as profile_img_url
            FROM users u
            LEFT JOIN recommends r ON u.id = r.user_id
            LEFT JOIN albums a ON u.profile_album_id = a.id
            WHERE u.id = :user_id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserLikeArtist($user_id)
    {
        $sql = "
            SELECT a.id, a.flo_id, a.name, a.img_url, COUNT(a.id) as count
            FROM users u
            JOIN recommends r ON u.id = r.user_id
            JOIN songs s ON r.song_id = s.id
            JOIN song_artists sa ON s.id = sa.song_id
            JOIN artists a ON sa.artist_id = a.id
            WHERE u.id = :user_id
            GROUP BY a.id
            ORDER BY count DESC
            LIMIT 5
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserLikeGenre($user_id)
    {
        $sql = "
            SELECT genre_list.genre, COUNT(genre_list.genre) as count
            FROM users u
            JOIN recommends r ON u.id = r.user_id
            JOIN (
                SELECT s.id, replace(temp_table.genre, ' ', '') as genre
                FROM songs s
                JOIN json_table(
                    replace(json_array(s.genre), ',', '\",\"'),
                    '$[*]' columns (genre varchar(50) path '$')
                ) temp_table
            ) genre_list ON r.song_id = genre_list.id
            WHERE u.id = :user_id
            GROUP BY genre_list.genre
            ORDER BY count DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);
        $genre_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($genre_list as $key => $genre) {
            if ($key < 5) {
                $result[$genre['genre']] = $genre['count'];
            } else {
                $result['기타'] += $genre['count'];
            }
        }

        return $result;
    }

    public function setProfileAlbum($album_id)
    {
        $sql = "
            UPDATE users 
            SET profile_album_id = :album_id 
            WHERE id = :user_id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['album_id' => $album_id, 'user_id' => $_SESSION['user']['id']]);
    }
}
