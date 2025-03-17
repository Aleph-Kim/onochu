<?

class Albums extends Model
{
    public function insert($album)
    {
        try {
            $sql = "INSERT INTO albums (artist_id, title, release_date, img_url, flo_id) 
                    VALUES (:artist_id, :title, :release_date, :img_url, :flo_id)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':artist_id' => $album['artist_id'],
                ':title' => $album['title'],
                ':release_date' => $album['release_date'],
                ':img_url' => $album['img_url'],
                ':flo_id' => $album['flo_id']
            ]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Album insertion failed: " . $e->getMessage());
        }
    }
}
