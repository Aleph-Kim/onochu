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
}
