<?

class Recommends extends Model
{
    public function insert($recommend)
    {
        try {
            $sql = "INSERT INTO recommends (song_id, user_id, score, comment) 
                    VALUES (:song_id, :user_id, :score, :comment)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':song_id' => $recommend['song_id'],
                ':user_id' => $recommend['user_id'],
                ':score' => $recommend['score'],
                ':comment' => $recommend['comment']
            ]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            ErrorHandler::showErrorPage(400);
        }
    }

    public function getRecentRecommends($limit = 10)
    {
        try {
            $sql = "
                SELECT 
                    r.id,
                    s.id AS song_id,
                    s.title AS song_title,
                    s.album_id,
                    a.img_url AS album_img_url,
                    SUBSTRING_INDEX(GROUP_CONCAT(art.id), ',', 1) AS artist_id,
                    GROUP_CONCAT(art.name SEPARATOR ' & ') AS artist_name,
                    SUBSTRING_INDEX(GROUP_CONCAT(art.img_url), ',', 1) AS artist_img_url
                FROM (
                    SELECT id, song_id, created_at
                    FROM recommends
                    ORDER BY created_at DESC
                    LIMIT :limit
                ) AS r
                JOIN songs s ON r.song_id = s.id
                JOIN song_artists sa ON s.id = sa.song_id
                JOIN artists art ON sa.artist_id = art.id
                JOIN albums a ON s.album_id = a.id
                GROUP BY r.id, s.id, s.title, s.album_id, a.img_url
                ORDER BY r.created_at DESC
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            ErrorHandler::showErrorPage(400);
        }
    }
}
