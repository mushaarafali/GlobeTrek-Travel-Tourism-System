<?php

require_once __DIR__ . '/../core/Database.php';

class TravelGuide
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function all()
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM travel_guides
            ORDER BY id DESC
        ");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM travel_guides
            WHERE id = ?
            LIMIT 1
        ");
        $stmt->execute([(int)$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = "INSERT INTO travel_guides
                (title, location, category, best_time, description, image)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $data['title'],
            $data['location'],
            $data['category'],
            $data['best_time'],
            $data['description'],
            $data['image']
        ]);
    }

    public function update($id, $data)
    {
        $sql = "UPDATE travel_guides SET
                    title = ?,
                    location = ?,
                    category = ?,
                    best_time = ?,
                    description = ?,
                    image = ?
                WHERE id = ?";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $data['title'],
            $data['location'],
            $data['category'],
            $data['best_time'],
            $data['description'],
            $data['image'],
            (int)$id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("
            DELETE FROM travel_guides
            WHERE id = ?
        ");

        return $stmt->execute([(int)$id]);
    }
}