<style>
    html {
        margin: 0;
        padding: 0;
        overflow-x: hidden;
        overscroll-behavior: none;  
        /* touch-action: none;  */
    }
</style>

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
    <title>📊 Sales Forecast Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background: #f4f7fa;
            color: #333;
        }
        header {
            background: #1e293b;
            padding: 25px 0;
            color: white;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        h1 {
            margin: 0;
            font-size: 28px;
        }
        .form-section {
            text-align: center;
            margin: 40px auto;
        }
        .form-box {
            background: white;
            padding: 20px 30px;
            border-radius: 15px;
            display: inline-block;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        .form-box label {
            margin: 0 15px;
            font-weight: 500;
            font-size: 16px;
        }
        .form-box input[type="submit"] {
            margin-left: 20px;
            padding: 10px 24px;
            font-size: 16px;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        .metrics {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin: 30px auto;
            width: 90%;
        }
        .metric-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.07);
            text-align: center;
            width: 250px;
            transition: transform 0.3s;
        }
        .metric-card:hover {
            transform: translateY(-4px);
        }
        .metric-card h3 {
            margin-bottom: 10px;
            font-size: 18px;
            color: #555;
        }
        .metric-card p {
            font-size: 22px;
            font-weight: 600;
            color: #111;
        }
        table {
            width: 95%;
            margin: 30px auto;
            border-collapse: collapse;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        th, td {
            padding: 14px;
            text-align: center;
        }
        th {
            background: #3b82f6;
            color: white;
        }
        tr:nth-child(even) {
            background: #f9fafb;
        }
        tr:hover {
            background: #e0f2fe;
        }
        .chart-container {
            width: 90%;
            margin: 40px auto;
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        }
        canvas {
            margin-top: 15px;
        }
        .error {
            text-align: center;
            color: red;
            font-weight: bold;
            margin-top: 20px;
        }
        h4 {
            text-align: center;
            margin-top: 40px;
            font-size: 20px;
            color: #333;
        }
    </style>
</head>
<body>
    <header>
        <h1>📊 Prediction Dashboard</h1>
    </header>

    <section class="form-section">
        <form method="post" class="form-box">
            <label><input type="radio" name="range" value="60" required> Last 60 Days</label>
            <label><input type="radio" name="range" value="90"> Last 90 Days</label>
            <label><input type="radio" name="range" value="120"> Last 120 Days</label>
            <input type="submit" value="Analyze">
        </form>
    </section>

    <?php if (isset($data)): ?>
        <?php if (isset($data['error'])): ?>
            <div class="error">Error: <?= htmlspecialchars($data['error']) ?></div>
        <?php else: ?>
            <section class="metrics">
                <div class="metric-card">
                    <h3>Total Revenue</h3>
                    <p><?= htmlspecialchars($data['Overall Sales Summary']['Total Revenue in Selected Range']) ?> RS</p>
                </div>
                <div class="metric-card">
                    <h3>Predicted Revenue</h3>
                    <p><?= htmlspecialchars($data['Overall Sales Summary']['Predicted Overall Sales for Next Month (Revenue)']) ?> RS</p>
                </div>
                <div class="metric-card">
                    <h3>Total Quantity</h3>
                    <p><?= htmlspecialchars($data['Overall Sales Summary']['Total Sales in Selected Range']) ?> Units</p>
                </div>
                <div class="metric-card">
                    <h3>Predicted Quantity</h3>
                    <p><?= htmlspecialchars($data['Overall Sales Summary']['Predicted Overall Sales for Next Month (Qty)']) ?> Units</p>
                </div>
            </section>
            <br>
            <br>

            <h4>📦 Product-wise Forecast, Total Sold, and Sales Price</h4>
            <table>
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Product Name</th>
                        <th>Total Quantity Sold</th>
                        <th>Total Sales Price</th>
                        <th>Predicted Quantity</th>
                        <th>Predicted Sales Price</th>
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
            <br>
            <br>


            <h4>📈Product-wise Forecast, Total Sold, and Sales Price </h4>
            <div class="chart-container">
                <img src="sales_trend.png" alt="Sales Chart">
            </div>
            <h4>📈 Product-wise Sales: Previous vs Predicted</h4>
            <div class="chart-container">
                <canvas id="multiLineChart"></canvas>
            </div>

            <script>
                const labels = ['Total Sales', 'Next Month'];
                const datasets = [];
                const brightColors = ['#FF6B6B', '#4ECDC4', '#FF9F1C', '#7F00FF', '#00B8D9'];
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
                        backgroundColor: brightColors[colorIndex % brightColors.length] + 'CC',
                        borderColor: brightColors[colorIndex % brightColors.length],
                        borderWidth: 2
                    });
                    colorIndex++;
                <?php endforeach; ?>

                new Chart(document.getElementById('multiLineChart'), {
                    type: 'bar',
                    data: { labels, datasets },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'bottom' },
                            tooltip: { mode: 'index', intersect: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: { display: true, text: 'Sales (RS)' }
                            }
                        }
                    }
                });

                // 🔥 Top 5 High-Priority Products Chart
                const productForecasts = <?= json_encode($data['Product-wise Forecasts']) ?>;
                const top5 = productForecasts.sort((a, b) => b.predicted_next_month - a.predicted_next_month).slice(0, 5);
                const topLabels = top5.map(p => p.product_name);
                const topData = top5.map(p => p.predicted_next_month);
                const colors = ['#e74c3c', '#f39c12', '#3498db', '#1abc9c', '#9b59b6'];

                const canvas = document.createElement('canvas');
                canvas.id = 'priorityChart';
                document.body.insertAdjacentHTML('beforeend', '<br><br><h4>🔥 Top 5 Priority Products</h4><div class="chart-container"></div>');
                document.querySelector('.chart-container:last-child').appendChild(canvas);

                new Chart(canvas, {
                    type: 'bar',
                    data: {
                        labels: topLabels,
                        datasets: [{
                            label: 'Predicted Qty (Next Month)',
                            data: topData,
                            backgroundColor: colors,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        plugins: {
                            legend: { display: false },
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