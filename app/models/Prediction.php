<?php

require_once __DIR__ . '/../core/Database.php';

class Prediction
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function monthlySales()
    {
        $sql = "SELECT * FROM monthly_sales_prediction ORDER BY sales_month ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function packagePerformance()
{
    $sql = "SELECT 
                p.title,

                COUNT(b.id) AS total_bookings,

                COALESCE(SUM(b.total_amount), 0) AS revenue

            FROM bookings b

            JOIN packages p 
                ON b.package_id = p.package_id

            WHERE b.status IN ('Confirmed', 'Paid')

            GROUP BY p.package_id, p.title

            ORDER BY total_bookings DESC";

    $stmt = $this->db->prepare($sql);

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function summary()
    {
        $sql = "SELECT 
                    COUNT(id) AS total_bookings,
                    SUM(total_amount) AS total_revenue,
                    AVG(total_amount) AS average_value
                FROM bookings
                WHERE status = 'Confirmed'";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function nextMonthPrediction()
    {
        $sales = $this->monthlySales();

        if (count($sales) === 0) {
            return [
                'predicted_revenue' => 0,
                'predicted_bookings' => 0,
                'growth_rate' => 0
            ];
        }

        $lastThree = array_slice($sales, -3);

        $revenueTotal = 0;
        $bookingTotal = 0;

        foreach ($lastThree as $row) {
            $revenueTotal += $row['total_revenue'];
            $bookingTotal += $row['total_bookings'];
        }

        $count = count($lastThree);

        $predictedRevenue = $revenueTotal / $count;
        $predictedBookings = round($bookingTotal / $count);

        $growthRate = 0;

        if (count($sales) >= 2) {
            $last = $sales[count($sales) - 1]['total_revenue'];
            $previous = $sales[count($sales) - 2]['total_revenue'];

            if ($previous > 0) {
                $growthRate = (($last - $previous) / $previous) * 100;
            }
        }

        return [
            'predicted_revenue' => $predictedRevenue,
            'predicted_bookings' => $predictedBookings,
            'growth_rate' => $growthRate
        ];
    }
}