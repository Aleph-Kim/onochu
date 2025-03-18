<?

class Artists extends Model
{
    public function insert($artist)
    {
        try {
            $sql = "INSERT INTO artists (name, img_url, flo_id) VALUES (:name, :img_url, :flo_id)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':name' => $artist['name'],
                ':img_url' => $artist['img_url'] ?: null,
                ':flo_id' => $artist['flo_id']
            ]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Artist insertion failed: " . $e->getMessage());
        }
    }

    public function getByFloId($flo_id)
    {
        try {
            $sql = "SELECT * FROM artists WHERE flo_id = :flo_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['flo_id' => $flo_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Failed to retrieve song: " . $e->getMessage());
        }
    }
}
