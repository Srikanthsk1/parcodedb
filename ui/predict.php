<?php
$data = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $range = $_POST['range'];
    $days = in_array($range, ['60', '90', '120']) ? (int)$range : 60;

    $to = date('Y-m-d');
    $from = date('Y-m-d', strtotime("-$days days"));

    $from_escaped = escapeshellarg($from);
    $to_escaped = escapeshellarg($to);

    // 📦 Build the command
    $cmd = "python model.py $from_escaped $to_escaped 2>&1"; // Redirect stderr to stdout

    // Execute the Python script
    $output = shell_exec($cmd);

    // 🧪 Log raw output for debugging
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
            <h3>📈 Overall Sales: <?= htmlspecialchars($data['total_sales']) ?> USD</h3>
            <h3>📈 Predicted Overall Sales for Next Month: <?= htmlspecialchars($data['predicted_next_month_sales']) ?> USD</h3>

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
                    <?php foreach ($data['products'] as $product): ?>
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
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
