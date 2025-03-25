<?

class Albums extends Model
{
    public function insert($album)
    {
        try {
            $sql = "INSERT INTO albums (title, release_date, img_url, flo_id) 
                    VALUES (:title, :release_date, :img_url, :flo_id)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':title' => $album['title'],
                ':release_date' => $album['release_date'],
                ':img_url' => $album['img_url'],
                ':flo_id' => $album['flo_id']
            ]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            ErrorHandler::showErrorPage(400);
        }
    }

    public function getByFloId($flo_id)
    {
        try {
            $sql = "SELECT * FROM albums WHERE flo_id = :flo_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['flo_id' => $flo_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            ErrorHandler::showErrorPage(400);
        }
    }
}
