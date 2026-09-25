<?php

require_once __DIR__ . '/../core/Model.php';

class Package extends Model
{
    public function all($filters = [])
    {
        $keyword  = trim($filters['q'] ?? '');
        $category = trim($filters['category'] ?? '');
        $maxPrice = trim($filters['max_price'] ?? '');

        $sql = 'SELECT * FROM packages WHERE 1=1';

        $params = [];

        if ($keyword !== '') {

            $sql .= ' AND (
                title LIKE ?
                OR destination LIKE ?
                OR category LIKE ?
                OR description LIKE ?
            )';

            $like = "%$keyword%";

            array_push($params, $like, $like, $like, $like);
        }

        if ($category !== '') {

            $sql .= ' AND category = ?';

            $params[] = $category;
        }

        if ($maxPrice !== '' && is_numeric($maxPrice)) {

            $sql .= ' AND price <= ?';

            $params[] = (float)$maxPrice;
        }

        $sql .= ' ORDER BY package_id DESC';

        $stmt = $this->db->prepare($sql);

        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function categories()
    {
        return $this->db
            ->query('
                SELECT DISTINCT category
                FROM packages
                ORDER BY category ASC
            ')
            ->fetchAll();
    }

    public function find($package_id)
    {
        if (!filter_var($package_id, FILTER_VALIDATE_INT)) {
            return false;
        }

        $stmt = $this->db->prepare('
            SELECT *
            FROM packages
            WHERE package_id = ?
        ');

        $stmt->execute([(int)$package_id]);

        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare('
            INSERT INTO packages
            (
                title,
                destination,
                category,
                days,
                price,
                image,
                description
            )

            VALUES (?, ?, ?, ?, ?, ?, ?)
        ');

        return $stmt->execute([
            $data['title'],
            $data['destination'],
            $data['category'],
            (int)$data['days'],
            (float)$data['price'],
            $data['image'],
            $data['description']
        ]);
    }

    public function update($package_id, $data)
    {
        $stmt = $this->db->prepare('
            UPDATE packages

            SET
                title = ?,
                destination = ?,
                category = ?,
                days = ?,
                price = ?,
                image = ?,
                description = ?

            WHERE package_id = ?
        ');

        return $stmt->execute([
            $data['title'],
            $data['destination'],
            $data['category'],
            (int)$data['days'],
            (float)$data['price'],
            $data['image'],
            $data['description'],
            (int)$package_id
        ]);
    }

    public function delete($package_id)
    {
        $stmt = $this->db->prepare('
            DELETE FROM packages
            WHERE package_id = ?
        ');

        return $stmt->execute([
            (int)$package_id
        ]);
    }
}
?>