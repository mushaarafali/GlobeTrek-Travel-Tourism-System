<?php

require_once __DIR__ . '/../core/Database.php';

class TripModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function create($data)
    {
        $sql = "INSERT INTO custom_trips
                (user_id, destination, travel_date, days, persons, budget, note, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $data['user_id'],
            $data['destination'],
            $data['travel_date'],
            $data['days'],
            $data['persons'],
            $data['budget'],
            $data['requirements'] ?? $data['notes'] ?? ''
        ]);
    }
    public function all()
    {
        $sql = "SELECT ct.*, u.name, u.email
                FROM custom_trips ct
                LEFT JOIN users u ON ct.user_id = u.id
                ORDER BY ct.id DESC";

        return $this->db->query($sql)->fetchAll();
    }

    public function byUser($userId)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM custom_trips
            WHERE user_id = ?
            ORDER BY id DESC
        ");

        $stmt->execute([(int)$userId]);

        return $stmt->fetchAll();
    }

    public function updateStatus($id, $status)
    {
        $stmt = $this->db->prepare("
            UPDATE custom_trips
            SET status = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $status,
            (int)$id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("
            DELETE FROM custom_trips
            WHERE id = ?
        ");

        return $stmt->execute([(int)$id]);
    }
   
    public function reply($id, $reply)
{
    $stmt = $this->db->prepare("
        UPDATE custom_trips
        SET staff_reply = ?
        WHERE id = ?
    ");

    return $stmt->execute([
        $reply,
        (int)$id
    ]);
    
}
public function find($id)
{
    $stmt = $this->db->prepare("
        SELECT 
            ct.*,
            u.name,
            u.email
        FROM custom_trips ct
        LEFT JOIN users u 
        ON ct.user_id = u.id
        WHERE ct.id = ?
        LIMIT 1
    ");

    $stmt->execute([(int)$id]);

    return $stmt->fetch();
}
public function replyOnlyPending($id, $reply, $status)
{
    $stmt = $this->db->prepare("
        UPDATE custom_trips
        SET staff_reply = ?, status = ?
        WHERE id = ?
        AND status = 'pending'
        AND (staff_reply IS NULL OR staff_reply = '')
    ");

    return $stmt->execute([$reply, $status, (int)$id]);
}
public function getByUserId($userId)
{
    $stmt = $this->db->prepare("
        SELECT *
        FROM custom_trips
        WHERE user_id = ?
        ORDER BY id DESC
    ");

    $stmt->execute([(int)$userId]);

    return $stmt->fetchAll();
}

}