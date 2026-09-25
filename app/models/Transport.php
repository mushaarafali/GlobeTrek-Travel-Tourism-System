<?php
require_once __DIR__ . '/../core/Model.php';

class Transport extends Model
{
    public function all()
    {
        return $this->db->query("SELECT * FROM transports ORDER BY id DESC")->fetchAll();
    }

    public function available()
    {
        return $this->db->query("SELECT * FROM transports WHERE status = 'Available' ORDER BY price_per_day ASC, vehicle_type ASC")->fetchAll();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("INSERT INTO transports (vehicle_type, vehicle_no, seats, price_per_day, driver_name, driver_phone, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            trim($data['vehicle_type'] ?? ''),
            trim($data['vehicle_no'] ?? ''),
            (int)($data['seats'] ?? 0),
            (float)($data['price_per_day'] ?? 0),
            trim($data['driver_name'] ?? ''),
            trim($data['driver_phone'] ?? ''),
            trim($data['status'] ?? 'Available')
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM transports WHERE id = ?");
        return $stmt->execute([(int)$id]);
    }
    public function find($id)
    {
    $stmt = $this->db->prepare("SELECT * FROM transports WHERE id = ? LIMIT 1");
    $stmt->execute([(int)$id]);
    return $stmt->fetch();
    }
    public function update($id, $data)
    {
        $stmt = $this->db->prepare("UPDATE transports SET vehicle_type=?, vehicle_no=?, seats=?, price_per_day=?, driver_name=?, driver_phone=?, status=? WHERE id=?");
        return $stmt->execute([
            trim($data['vehicle_type'] ?? ''),
            trim($data['vehicle_no'] ?? ''),
            
            (int)($data['seats'] ?? 0),
            (float)($data['price_per_day'] ?? 0),
            trim($data['driver_name'] ?? ''),
            trim($data['driver_phone'] ?? ''),
            trim($data['status'] ?? 'Available'),
            (int)$id
        ]);
    }

}
?>
