<?

class Artists extends Model
{
    public function insert($artist)
    {
        $sql = "INSERT INTO artists (name, genre, group_type, img_url, flo_id) VALUES (:name, :genre, :group_type, :img_url, :flo_id)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':name' => $artist['name'],
            ':genre' => $artist['genre'],
            ':group_type' => $artist['group_type'],
            ':img_url' => $artist['img_url'],
            ':flo_id' => $artist['flo_id']
        ]);
        return $this->db->lastInsertId();
    }

    public function getByFloId($flo_id)
    {
        try {
            $sql = "SELECT * FROM artists WHERE flo_id = :flo_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['flo_id' => $flo_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            ErrorHandler::showErrorPage(400);
        }
    }

    public function getUserArtists()
    {
        $sql = "
            SELECT a.flo_id, a.name, a.img_url, COUNT(*) as recommend_cnt
            FROM recommends r
            JOIN songs s ON r.song_id = s.id
            JOIN song_artists sa ON s.id = sa.song_id
            JOIN artists a ON sa.artist_id = a.id
            WHERE r.user_id = :user_id
            GROUP BY a.flo_id, a.name, a.img_url
            ORDER BY recommend_cnt DESC, max(r.created_at) DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $_SESSION['user']['id']]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPopularArtists()
    {
        $sql = "
            SELECT a.flo_id, a.name, a.img_url, COUNT(*) as recommend_cnt
            FROM recommends r
            JOIN songs s ON r.song_id = s.id
            JOIN song_artists sa ON s.id = sa.song_id
            JOIN artists a ON sa.artist_id = a.id
            GROUP BY a.flo_id, a.name, a.img_url
            ORDER BY recommend_cnt DESC, max(r.created_at) DESC
            LIMIT 20
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
