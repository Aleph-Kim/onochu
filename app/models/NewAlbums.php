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

/**
 * 최근 7일간 발매된 앨범과 그 앨범의 아티스트 목록 조회
 *
 * @return array 앨범 목록과 아티스트 flo_id 배열
 */
public function getRecentAlbumsAndArtists()
{
    // 앨범과 아티스트를 단일 쿼리로 조회
    $sql = "
        SELECT 
            album.id, 
            album.album_title, 
            album.album_img_url, 
            album.flo_id AS album_flo_id, 
            artist.artist_name AS artist_name, 
            artist.flo_id AS artist_flo_id
        FROM new_albums album
        LEFT JOIN new_album_artists artist ON artist.new_album_id = album.id
        WHERE album.created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
        ORDER BY album.created_at DESC
    ";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}
