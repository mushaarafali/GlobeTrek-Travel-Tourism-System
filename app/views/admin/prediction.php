<?php require __DIR__ . '/../layouts/head.php'; ?>

<section class="prediction-page">

    <div class="page-head prediction-head">
        <h1>Future Booking & Sales Prediction</h1>
        <p>Analyze confirmed bookings and predict upcoming travel sales performance.</p>
    </div>

    <div class="prediction-stats">

        <div class="prediction-card">
            <span>Total Revenue</span>
            <strong>LKR <?= number_format($summary['total_revenue'] ?? 0, 2) ?></strong>
        </div>

        <div class="prediction-card">
            <span>Total Bookings</span>
            <strong><?= e($summary['total_bookings'] ?? 0) ?></strong>
        </div>

        <div class="prediction-card">
            <span>Average Booking Value</span>
            <strong>LKR <?= number_format($summary['average_value'] ?? 0, 2) ?></strong>
        </div>

        <div class="prediction-card highlight">
            <span>Next Month Prediction</span>
            <strong>LKR <?= number_format($prediction['predicted_revenue'] ?? 0, 2) ?></strong>
        </div>

    </div>

    <div class="prediction-insight">
        <h2>Business Insight</h2>

        <p>
            Expected bookings for next month:
            <strong><?= e($prediction['predicted_bookings'] ?? 0) ?></strong>
        </p>

        <p>
            Current growth rate:
            <strong><?= number_format($prediction['growth_rate'] ?? 0, 2) ?>%</strong>
        </p>
    </div>

    <div class="chart-grid">

        <div class="chart-card">
            <h2>Monthly Revenue Trend</h2>
            <canvas id="revenueChart"></canvas>
        </div>

        <div class="chart-card">
            <h2>Monthly Booking Trend</h2>
            <canvas id="bookingChart"></canvas>
        </div>

    </div>

    <div class="table-card prediction-table">
        <h2>Package Performance</h2>

        <table>
            <thead>
                <tr>
                    <th>Package</th>
                    <th>Total Bookings</th>
                    <th>Revenue</th>
                    <th>Trend</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($packages)): ?>
                    <?php foreach ($packages as $p): ?>
                        <tr>
                            <td><?= e($p['title']) ?></td>
                            <td><?= e($p['total_bookings']) ?></td>
                            <td>LKR <?= number_format($p['revenue'], 2) ?></td>
                            <td>
                                <?php if ($p['total_bookings'] >= 5): ?>
                                    <span class="trend high">High Demand</span>
                                <?php elseif ($p['total_bookings'] >= 2): ?>
                                    <span class="trend medium">Medium</span>
                                <?php else: ?>
                                    <span class="trend low">Low</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align:center;">No package performance data found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const monthlyLabels = <?= json_encode(array_column($monthlySales, 'sales_month')) ?>;
const revenueData = <?= json_encode(array_map('floatval', array_column($monthlySales, 'total_revenue'))) ?>;
const bookingData = <?= json_encode(array_map('intval', array_column($monthlySales, 'total_bookings'))) ?>;

new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels: monthlyLabels,
        datasets: [{
            label: 'Revenue LKR',
            data: revenueData,
            tension: 0.35,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: true
            }
        }
    }
});

new Chart(document.getElementById('bookingChart'), {
    type: 'bar',
    data: {
        labels: monthlyLabels,
        datasets: [{
            label: 'Bookings',
            data: bookingData
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: true
            }
        }
    }
});
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>