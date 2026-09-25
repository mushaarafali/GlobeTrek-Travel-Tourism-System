<?php
require_once __DIR__ . '/../core/Model.php';
class Inquiry extends Model {
    public function create($userId, $subject, $message) {
        $stmt = $this->db->prepare('INSERT INTO inquiries (user_id,subject,message,status) 
        VALUES (?,?,?,?)');
        return $stmt->execute([$userId,$subject,$message,'Open']);
    }
    public function all() {
        return $this->db->query('SELECT i.*, u.name user_name FROM inquiries i JOIN users u ON i.user_id=u.id ORDER BY i.id DESC')->fetchAll();
    }
    public function reply($id, $reply) {
        $stmt = $this->db->prepare('UPDATE inquiries SET reply=?, status=? WHERE id=?');
        return $stmt->execute([$reply,'Answered',$id]);
    }
    public function replyOnlyOnce($id, $reply)
{
    $stmt = $this->db->prepare("
        UPDATE inquiries
        SET reply = ?, status = 'Replied'
        WHERE id = ?
        AND (reply IS NULL OR reply = '')
    ");

    return $stmt->execute([$reply, (int)$id]);
}
    public function getByUserId($userId)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM inquiries
            WHERE user_id = ?
            ORDER BY id DESC
        ");

        $stmt->execute([(int)$userId]);

        return $stmt->fetchAll();
    }
}
?>
