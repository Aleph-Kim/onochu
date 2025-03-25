<?

use Predis\Response\Error;

class Songs extends Model
{
    public function insert($song)
    {
        try {
            $sql = "INSERT INTO songs (album_id, title, genre, title_yn, play_time, lyrics, composer, lyricist, arranger, flo_id) 
                    VALUES (:album_id, :title, :genre, :title_yn, :play_time, :lyrics, :composer, :lyricist, :arranger, :flo_id)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':album_id' => $song['album_id'],
                ':title' => $song['title'],
                ':genre' => $song['genre'],
                ':title_yn' => $song['title_yn'],
                ':play_time' => $song['play_time'],
                ':lyrics' => $song['lyrics'],
                ':composer' => $song['composer'],
                ':lyricist' => $song['lyricist'],
                ':arranger' => $song['arranger'],
                ':flo_id' => $song['flo_id']
            ]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            ErrorHandler::showErrorPage(400);
        }
    }

    public function getByFloId($flo_id)
    {
        try {
            $sql = "SELECT * FROM songs WHERE flo_id = :flo_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['flo_id' => $flo_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            ErrorHandler::showErrorPage(400);
        }
    }

    /**
     * 노래와 아티스트 관계 저장
     * @param int $song_id 노래 id
     * @param int $artist_id 아티스트 id
     */
    public function insertSongArtistRelation($song_id, $artist_id)
    {
        try {
            $sql = "INSERT INTO song_artists (song_id, artist_id) VALUES (:song_id, :artist_id)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':song_id' => $song_id,
                ':artist_id' => $artist_id
            ]);
        } catch (PDOException $e) {
            ErrorHandler::showErrorPage(400);
        }
    }
}
