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
        $sql = "
            SELECT 
                recommends.id,
                songs.id as song_id,
                songs.title as song_title,
                songs.album_id,
                albums.img_url as album_img_url,
                artists.id as artist_id,
                artists.name as artist_name,
                artists.img_url as artist_img_url
            FROM recommends 
            JOIN songs ON recommends.song_id = songs.id
            JOIN song_artists ON songs.id = song_artists.song_id
            JOIN artists ON song_artists.artist_id = artists.id
            JOIN albums ON songs.album_id = albums.id
            ORDER BY recommends.created_at DESC 
            LIMIT $limit
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
