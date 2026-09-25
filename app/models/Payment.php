<?php
require_once __DIR__ . '/../core/Model.php';

class Payment extends Model
{
    public function create($data)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO payments 
            (booking_id, user_id, card_holder, card_last4, expiry_month, expiry_year, amount, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );

        return $stmt->execute([
            $data['booking_id'],
            $data['user_id'],
            $data['card_holder'],
            $data['card_last4'],
            $data['expiry_month'],
            $data['expiry_year'],
            $data['amount'],
            'Paid'
        ]);
    }
    public function findByBooking($bookingId)
    {
        $stmt = $this->db->prepare("SELECT * FROM payments WHERE booking_id = ? ORDER BY id DESC LIMIT 1");
        $stmt->execute([(int)$bookingId]);
        return $stmt->fetch();
    }

    public function summary()
    {
        return $this->db->query("SELECT status, COUNT(*) total, COALESCE(SUM(amount),0) amount FROM payments GROUP BY status ORDER BY amount DESC")->fetchAll();
    }

}
?>