<?php

require_once __DIR__ . '/../core/Model.php';

class Accommodation extends Model
{
    protected $table = 'accommodations';

    public function all()
    {
        return $this->db->query("
            SELECT a.*, p.title AS package_title
            FROM accommodations a
            JOIN packages p ON p.package_id = a.package_id
            ORDER BY a.id DESC
        ")->fetchAll();
    }

    public function byPackage($packageId)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM accommodations
            WHERE package_id = ?
            AND status = 'Available'
        ");

        $stmt->execute([(int)$packageId]);

        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM accommodations
            WHERE id = ?
            LIMIT 1
        ");

        $stmt->execute([(int)$id]);

        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO accommodations
            (package_id, hotel_name, room_type, room_price, max_people, description, status)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            (int)($data['package_id'] ?? 0),
            trim($data['hotel_name'] ?? ''),
            trim($data['room_type'] ?? ''),
            (float)($data['room_price'] ?? 0),
            (int)($data['max_people'] ?? 1),

            trim($data['description'] ?? ''),
            trim($data['status'] ?? 'Available')
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE accommodations
            SET
                package_id = ?,
                hotel_name = ?,
                room_type = ?,
                room_price = ?,
                max_people = ?,
               
                description = ?,
                status = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            (int)($data['package_id'] ?? 0),
            trim($data['hotel_name'] ?? ''),
            trim($data['room_type'] ?? ''),
            (float)($data['room_price'] ?? 0),
            (int)($data['max_people'] ?? 1),
      
            trim($data['description'] ?? ''),
            trim($data['status'] ?? 'Available'),
            (int)$id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("
            DELETE FROM accommodations
            WHERE id = ?
        ");

        return $stmt->execute([(int)$id]);
    }
}
?>