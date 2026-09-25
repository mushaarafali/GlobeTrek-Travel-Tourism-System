<?php

require_once __DIR__ . '/../core/Model.php';

class Booking extends Model
{
    public function create($userId, $data)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO bookings
            (
                user_id,
                package_id,
                travel_date,
                persons,
                custom_note,
                status,
                total_amount,
                payment_method,
                accommodation_id,
                transport_id
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $stmt->execute([
            (int)$userId,
            (int)$data['package_id'],
            $data['travel_date'],
            (int)$data['persons'],
            $data['custom_note'] ?? '',
            'Pending',
            (float)$data['total_amount'],
            $data['payment_method'] ?? 'Pending',
            !empty($data['accommodation_id']) ? (int)$data['accommodation_id'] : null,
            !empty($data['transport_id']) ? (int)$data['transport_id'] : null
        ]);

        return $this->db->lastInsertId();
    }

    public function updatePaymentMethod($id, $method)
    {
        $stmt = $this->db->prepare(
            "UPDATE bookings SET payment_method = ? WHERE id = ?"
        );

        return $stmt->execute([$method, (int)$id]);
    }

    public function all()
    {
        return $this->db->query(
            "SELECT 
                b.*,
                u.name AS user_name,
                u.email AS user_email,

                p.title AS package_title,
                p.destination,
                p.price AS package_price,
                p.price,
                p.days,

                a.hotel_name AS accommodation_name,
                a.hotel_name,
                a.room_type,
                a.room_price AS accommodation_price,
                a.room_price,
                a.max_people,

                t.vehicle_no AS transport_name,
                t.vehicle_no,
                t.vehicle_type,
                t.seats AS transport_seats,
                t.seats,
                t.price_per_day AS transport_price,
                t.price_per_day

            FROM bookings b

            JOIN users u 
                ON b.user_id = u.id

            JOIN packages p 
                ON b.package_id = p.package_id

            LEFT JOIN accommodations a
                ON b.accommodation_id = a.id

            LEFT JOIN transports t 
                ON b.transport_id = t.id

            ORDER BY b.id DESC"
        )->fetchAll();
    }

    public function byUser($userId)
    {
        $stmt = $this->db->prepare(
            "SELECT 
                b.*,
                u.name AS user_name,
                u.email AS user_email,

                p.title AS package_title,
                p.destination,
                p.price AS package_price,
                p.price,
                p.days,

                a.hotel_name AS accommodation_name,
                a.hotel_name,
                a.room_type,
                a.room_price AS accommodation_price,
                a.room_price,
                a.max_people,

                t.vehicle_no AS transport_name,
                t.vehicle_no,
                t.vehicle_type,
                t.seats AS transport_seats,
                t.seats,
                t.price_per_day AS transport_price,
                t.price_per_day

            FROM bookings b

            LEFT JOIN users u 
                ON b.user_id = u.id

            LEFT JOIN packages p 
                ON b.package_id = p.package_id

            LEFT JOIN accommodations a
                ON b.accommodation_id = a.id

            LEFT JOIN transports t 
                ON b.transport_id = t.id

            WHERE b.user_id = ?

            ORDER BY b.id DESC"
        );

        $stmt->execute([(int)$userId]);

        return $stmt->fetchAll();
    }

    public function getByUserId($userId)
    {
        return $this->byUser($userId);
    }

    public function findWithDetails($id)
    {
        $stmt = $this->db->prepare(
            "SELECT 
                b.*,
                u.name AS user_name,
                u.email AS user_email,

                p.title AS package_title,
                p.destination,
                p.price AS package_price,
                p.price,
                p.days,

                a.hotel_name AS accommodation_name,
                a.hotel_name,
                a.room_type,
                a.room_price AS accommodation_price,
                a.room_price,
                a.room_price AS price_per_night,
                a.max_people,

                t.vehicle_no AS transport_name,
                t.vehicle_no,
                t.vehicle_type,
                t.seats AS transport_seats,
                t.seats,
                t.price_per_day AS transport_price,
                t.price_per_day

            FROM bookings b

            LEFT JOIN users u 
                ON b.user_id = u.id

            LEFT JOIN packages p 
                ON b.package_id = p.package_id

            LEFT JOIN accommodations a
                ON b.accommodation_id = a.id

            LEFT JOIN transports t 
                ON b.transport_id = t.id

            WHERE b.id = ?

            LIMIT 1"
        );

        $stmt->execute([(int)$id]);

        return $stmt->fetch();
    }

    public function updateStatus($id, $status)
    {
        $stmt = $this->db->prepare(
            "UPDATE bookings SET status = ? WHERE id = ?"
        );

        return $stmt->execute([$status, (int)$id]);
    }

    public function count()
    {
        return $this->db->query(
            "SELECT COUNT(*) AS c FROM bookings"
        )->fetch()['c'];
    }

    public function revenue()
    {
        return $this->db->query(
            "SELECT COALESCE(SUM(total_amount), 0) AS t
             FROM bookings
             WHERE status != 'Cancelled'"
        )->fetch()['t'];
    }

    public function monthlySummary()
    {
        return $this->db->query(
            "SELECT 
                DATE_FORMAT(created_at, '%Y-%m') AS month,
                COUNT(*) AS bookings,
                COALESCE(SUM(total_amount), 0) AS revenue
            FROM bookings
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
            ORDER BY month ASC"
        )->fetchAll();
    }

    public function futurePrediction()
    {
        $rows = $this->monthlySummary();
        $count = count($rows);

        if ($count === 0) {
            return [
                'averageBookings' => 0,
                'averageRevenue' => 0,
                'predictedBookings' => 0,
                'predictedRevenue' => 0,
                'trend' => 'No data available yet',
                'rows' => []
            ];
        }

        $totalBookings = array_sum(array_column($rows, 'bookings'));
        $totalRevenue  = array_sum(array_column($rows, 'revenue'));

        $avgBookings = $totalBookings / $count;
        $avgRevenue  = $totalRevenue / $count;

        $lastBookings = (float)$rows[$count - 1]['bookings'];
        $lastRevenue  = (float)$rows[$count - 1]['revenue'];

        $growthBookings = $count > 1
            ? $lastBookings - (float)$rows[$count - 2]['bookings']
            : 1;

        $growthRevenue = $count > 1
            ? $lastRevenue - (float)$rows[$count - 2]['revenue']
            : ($avgRevenue * 0.10);

        return [
            'averageBookings'   => round($avgBookings, 2),
            'averageRevenue'    => round($avgRevenue, 2),
            'predictedBookings' => max(0, round($avgBookings + $growthBookings)),
            'predictedRevenue'  => max(0, round($avgRevenue + $growthRevenue, 2)),
            'trend'             => $growthRevenue >= 0
                ? 'Growing / Positive demand'
                : 'Reducing / Needs promotion',
            'rows'              => $rows
        ];
    }

    public function cancelByCustomer($bookingId, $userId)
    {
        $stmt = $this->db->prepare(
            "UPDATE bookings
             SET status = 'Cancelled'
             WHERE id = ?
             AND user_id = ?"
        );

        return $stmt->execute([
            (int)$bookingId,
            (int)$userId
        ]);
    }

    public function revenueSummary()
    {
        return $this->db->query(
            "SELECT 
                COALESCE(SUM(total_amount), 0) AS total_revenue,
                COUNT(*) AS total_bookings,
                COALESCE(AVG(total_amount), 0) AS average_order
            FROM bookings
            WHERE status != 'Cancelled'"
        )->fetch();
    }

    public function statusSummary()
    {
        return $this->db->query(
            "SELECT 
                status,
                COUNT(*) AS total,
                COALESCE(SUM(total_amount), 0) AS revenue
            FROM bookings
            GROUP BY status
            ORDER BY total DESC"
        )->fetchAll();
    }

    public function packagePerformance()
    {
        return $this->db->query(
            "SELECT 
                p.title,
                p.destination,
                COUNT(b.id) AS bookings,
                COALESCE(SUM(b.total_amount), 0) AS revenue
            FROM packages p
            LEFT JOIN bookings b
                ON b.package_id = p.package_id
                AND b.status != 'Cancelled'
            GROUP BY p.package_id, p.title, p.destination
            ORDER BY revenue DESC, bookings DESC"
        )->fetchAll();
    }

    public function existsForCustomerDate($userId, $travelDate)
    {
        $stmt = $this->db->prepare(
            "SELECT id
             FROM bookings
             WHERE user_id = ?
             AND travel_date = ?
             AND status != 'Cancelled'
             LIMIT 1"
        );

        $stmt->execute([
            (int)$userId,
            $travelDate
        ]);

        return $stmt->fetch() ? true : false;
    }
}
?>