<?

class Songs extends Model
{
    public function insert($song)
    {
        try {
            $sql = "INSERT INTO songs (album_id, artist_id, title, genre, flo_id) 
                    VALUES (:album_id, :artist_id, :title, :genre, :flo_id)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':album_id' => $song['album_id'],
                ':artist_id' => $song['artist_id'],
                ':title' => $song['title'],
                ':genre' => $song['genre'],
                ':flo_id' => $song['flo_id']
            ]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Song insertion failed: " . $e->getMessage());
        }
    }
}
