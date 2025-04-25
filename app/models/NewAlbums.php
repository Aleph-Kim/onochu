<?

class NewAlbums extends Model
{
    public function insert($data)
    {
        $sql = "INSERT INTO new_albums (album_title, album_img_url, flo_id, created_at)
                VALUES (:album_title, :album_img_url, :flo_id, :created_at)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'album_title' => $data['album_title'],
            'album_img_url' => $data['album_img_url'],
            'flo_id' => $data['flo_id'],
            'created_at' => $data['created_at'] ?? date('Y-m-d H:i:s')
        ]);

        return $this->db->lastInsertId();
    }
    /**
     * 아티스트 ID로 앨범 목록 조회
     */
    public function getByFloId($floId)
    {
        $sql = "SELECT * FROM new_albums
                WHERE flo_id = :flo_id
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['flo_id' => $floId]);
        return $stmt->fetch();
    }
}
