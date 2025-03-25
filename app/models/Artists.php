<?

class Artists extends Model
{
    public function insert($artist)
    {
            $sql = "INSERT INTO artists (name, genre, group_type, img_url, flo_id) VALUES (:name, :genre, :group_type, :img_url, :flo_id)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':name' => $artist['name'],
                ':genre' => $artist['genre'],
                ':group_type' => $artist['group_type'],
                ':img_url' => $artist['img_url'],
                ':flo_id' => $artist['flo_id']
            ]);
            return $this->db->lastInsertId();
    }

    public function getByFloId($flo_id)
    {
        try {
            $sql = "SELECT * FROM artists WHERE flo_id = :flo_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['flo_id' => $flo_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            ErrorHandler::showErrorPage(400);
        }
    }
}
