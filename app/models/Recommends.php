<?

class Recommends extends Model
{
    public function insert($recommend)
    {
        try {
            $sql = "INSERT INTO recommends (song_id, user_id, score, comment) 
                    VALUES (:song_id, :user_id, :score, :comment)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':song_id' => $recommend['song_id'],
                ':user_id' => $recommend['user_id'],
                ':score' => $recommend['score'],
                ':comment' => $recommend['comment']
            ]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            ErrorHandler::showErrorPage(400);
        }
    }
}
