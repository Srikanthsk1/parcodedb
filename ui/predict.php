<?php
$data = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $range = $_POST['range'];
    $days = in_array($range, ['60', '90', '120']) ? (int)$range : 60;

    $to = date('Y-m-d');
    $from = date('Y-m-d', strtotime("-$days days"));

    $from_escaped = escapeshellarg($from);
    $to_escaped = escapeshellarg($to);

    $cmd = "python model.py $from_escaped $to_escaped 2>&1";
    $output = shell_exec($cmd);

    file_put_contents('python_output_log.txt', "CMD: $cmd\n\nOutput:\n$output\n\n", FILE_APPEND);

    if ($output === null) {
        $data = ['error' => '❌ Failed to execute the Python script.'];
    } else {
        $decoded = json_decode($output, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $data = $decoded;
        } else {
            file_put_contents('python_error_log.txt', "Raw Output:\n$output\nJSON Error: " . json_last_error_msg() . "\n", FILE_APPEND);
            $data = ['error' => '⚠️ Python script returned invalid JSON. Check python_error_log.txt.'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Forecast & Product Priority</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2 { color: #333; text-align: center; }
        form { margin-bottom: 20px; text-align: center; }
        label { margin: 0 10px; }
        input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            margin-top: 20px;
        }
        h3, h4 { margin-top: 20px; text-align: center; }
        table {
            border-collapse: collapse;
            width: 80%;
            margin: 20px auto;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: center;
        }
        th { background-color: #f2f2f2; }
        img {
            display: block;
            margin: 30px auto;
            border: 1px solid #ddd;
            padding: 5px;
        }
        .error { color: red; text-align: center; }
        .chart-container {
            width: 80%;
            margin: 40px auto;
        }
    </style>
</head>
<body>
    <h2>Sales Forecasting & Product Analysis</h2>
    <form method="post">
        <label><input type="radio" name="range" value="60" required> Last 60 Days</label>
        <label><input type="radio" name="range" value="90"> Last 90 Days</label>
        <label><input type="radio" name="range" value="120"> Last 120 Days</label><br>
        <input type="submit" value="Analyze">
    </form>

    <?php if (isset($data)): ?>
        <?php if (isset($data['error'])): ?>
            <h3 class="error">Error: <?= htmlspecialchars($data['error']) ?></h3>
        <?php else: ?>
            <h3>📈 Overall Sales: <?= htmlspecialchars($data['Overall Sales Summary']['Total Revenue in Selected Range']) ?> RS</h3>
            <h3>📈 Predicted Overall Sales for Next Month: <?= htmlspecialchars($data['Overall Sales Summary']['Predicted Overall Sales for Next Month (Revenue)']) ?> RS</h3>
            <h3>📈 Overall Quantity: <?= htmlspecialchars($data['Overall Sales Summary']['Total Sales in Selected Range']) ?> (Quantity)</h3>
            <h3>📈 Predicted Overall Quantity for Next Month: <?= htmlspecialchars($data['Overall Sales Summary']['Predicted Overall Sales for Next Month (Qty)']) ?> (Quantity)</h3>
            <h4>📦 Product-wise Forecast, Total Sold, and Sales Price</h4>
            <table>
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Product Name</th>
                        <th>Total Quantity Sold</th>
                        <th>Total Sales Price</th>
                        <th>Predicted Quantity for Next Month</th>
                        <th>Predicted Sales Price for Next Month</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['Product-wise Forecasts'] as $product): ?>
                        <?php
                            $predicted_sales_price = 0;
                            if ($product['total_sold_quantity'] > 0) {
                                $unit_price = $product['total_sales_price'] / $product['total_sold_quantity'];
                                $predicted_sales_price = $product['predicted_next_month'] * $unit_price;
                            }
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($product['product_id']) ?></td>
                            <td><?= htmlspecialchars($product['product_name']) ?></td>
                            <td><?= htmlspecialchars($product['total_sold_quantity']) ?></td>
                            <td><?= htmlspecialchars($product['total_sales_price']) ?> RS</td>
                            <td><?= htmlspecialchars($product['predicted_next_month']) ?></td>
                            <td><?= number_format($predicted_sales_price, 2) ?> RS</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h4>📊 Monthly Sales Trend</h4>
            <img src="sales_trend.png" alt="Sales Trend Graph" width="700">

            <h4>📈 Product-wise Sales: Previous vs Predicted (All in One Chart)</h4>
            <div class="chart-container">
                <canvas id="multiLineChart"></canvas>
            </div>

            <script>
                const labels = ['Total Sales', 'Next Month'];
                const datasets = [];
                const brightColors = ['#FF6B6B', '#4ECDC4', '#FF9F1C', '#7F00FF', '#00B8D9', '#36D399', '#F9A825', '#C51162', '#1DE9B6', '#FFD54F'];
                let colorIndex = 0;
                <?php foreach ($data['Product-wise Forecasts'] as $product): ?>
                    <?php
                        $predicted_sales_price = 0;
                        if ($product['total_sold_quantity'] > 0) {
                            $unit_price = $product['total_sales_price'] / $product['total_sold_quantity'];
                            $predicted_sales_price = $product['predicted_next_month'] * $unit_price;
                        }
                    ?>
                    datasets.push({
                        label: "<?= addslashes($product['product_name']) ?>",
                        data: [<?= $product['total_sales_price'] ?>, <?= $predicted_sales_price ?>],
                        borderColor: brightColors[colorIndex % brightColors.length],
                        backgroundColor: brightColors[colorIndex % brightColors.length] + 'CC',
                        fill: false,
                        tension: 0.4
                    });
                    colorIndex++;
                <?php endforeach; ?>
                new Chart(document.getElementById('multiLineChart'), {
                    type: 'bar',
                    data: { labels: labels, datasets: datasets },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'bottom' },
                            tooltip: { mode: 'index', intersect: false }
                        },
                        scales: {
                            y: { beginAtZero: true, title: { display: true, text: 'Sales in RS' } }
                        }
                    }
                });

                // 🔥 Top 5 High Priority Products (New Chart)
                const productForecasts = <?php echo json_encode($data['Product-wise Forecasts']); ?>;
                const sorted = productForecasts.sort((a, b) => b.predicted_next_month - a.predicted_next_month);
                const top5 = sorted.slice(0, 5);
                const priorityLabels = top5.map(p => p.product_name);
                const priorityData = top5.map(p => p.predicted_next_month);
                const priorityColors = ['#e74c3c', '#f39c12', '#3498db', '#1abc9c', '#9b59b6'];

                const priorityCtx = document.createElement('canvas');
                priorityCtx.id = 'priorityChart';
                document.body.insertAdjacentHTML('beforeend', '<h4>🔥 Top 5 High-Priority Products for Next Month (by Quantity)</h4><div class="chart-container"></div>');
                document.querySelector('.chart-container:last-child').appendChild(priorityCtx);

                new Chart(priorityCtx, {
                    type: 'bar',
                    data: {
                        labels: priorityLabels,
                        datasets: [{
                            label: 'Predicted Qty (Next Month)',
                            data: priorityData,
                            backgroundColor: priorityColors,
                            borderColor: priorityColors,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: ctx => `${ctx.parsed.x} Units`
                                }
                            },
                            title: {
                                display: true,
                                text: '💡 Restock These First!'
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Predicted Quantity'
                                }
                            }
                        }
                    }
                });
            </script>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>