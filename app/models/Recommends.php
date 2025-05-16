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
                    SUBSTRING_INDEX(GROUP_CONCAT(art.img_url), ',', 1) AS artist_img_url,
                    r.created_at AS recommend_date
                FROM (
                    SELECT MAX(id) AS id, song_id, MAX(created_at) as created_at
                    FROM recommends
                    GROUP BY song_id
                    ORDER BY MAX(created_at) DESC
                    LIMIT :limit
                ) AS r
                JOIN songs s ON r.song_id = s.id
                JOIN song_artists sa ON s.id = sa.song_id
                JOIN artists art ON sa.artist_id = art.id
                JOIN albums a ON s.album_id = a.id
                GROUP BY r.id, s.id, s.title, s.album_id, a.img_url, r.created_at
                ORDER BY r.id DESC;
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            ErrorHandler::showErrorPage(400);
        }
    }

    /**
     * 추천 상세 조회
     * @param int $id 추천 id
     */
    public function getById($id)
    {
        try {
            $recommend_sql = "
                SELECT 
                    recommends.id,
                    recommends.score,
                    recommends.comment,
                    recommends.created_at as recommend_date,
                    songs.id as song_id,
                    songs.title as song_title,
                    songs.album_id,
                    songs.lyrics,
                    songs.flo_id as song_flo_id,
                    albums.img_url as album_img_url,
                    albums.release_date as release_date,
                    albums.flo_id as album_flo_id,
                    songs.genre as genre,
                    songs.play_time as play_time
                FROM recommends 
                JOIN songs ON recommends.song_id = songs.id
                JOIN albums ON songs.album_id = albums.id
                WHERE recommends.id = :id
            ";
            $stmt = $this->db->prepare($recommend_sql);
            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $artist_sql = "
                SELECT 
                    artists.id,
                    artists.name,
                    artists.img_url,
                    artists.flo_id
                FROM song_artists
                JOIN artists ON song_artists.artist_id = artists.id
                WHERE song_artists.song_id = :song_id
            ";
            $stmt_artists = $this->db->prepare($artist_sql);
            $stmt_artists->execute([':song_id' => $result['song_id']]);
            $result['artists'] = $stmt_artists->fetchAll(PDO::FETCH_ASSOC);

            $song = [
                'flo_id' => $result['song_flo_id'],
                'title' => $result['song_title'],
            ];
            $result['url'] = PlatformHelper::getPlatformUrl($song, $result['artists']);

            return $result;
        } catch (PDOException $e) {
            ErrorHandler::showErrorPage(400);
        }
    }
}
