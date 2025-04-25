<?

class NewAlbumArtists extends Model
{
    public function insert($data)
    {
        $sql = "INSERT INTO new_album_artists (new_album_id, artist_name, flo_id)
                VALUES (:new_album_id, :artist_name, :flo_id)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'new_album_id' => $data['new_album_id'],
            'artist_name' => $data['artist_name'],
            'flo_id' => $data['flo_id']
        ]);

        return $this->db->lastInsertId();
    }
} 