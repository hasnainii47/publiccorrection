<?php

// Include database configuration file
require 'config.php';

// Connect to the database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Polygon API URL base and your API key
$apiKey = API_KEY;
$baseUrl = "https://api.polygon.io/v2/aggs/ticker/X:";

// List of cryptocurrencies to query
$cryptocurrencies = [
    ['name' => 'Bitcoin', 'symbol' => 'BTC'],
    ['name' => 'Ethereum', 'symbol' => 'ETH'],
    ['name' => 'Tether', 'symbol' => 'USDT']
];

// Date range for the query
$startDate = "2024-01-09";
$endDate=date('Y-m-d');

// Begin HTML table
echo "<table border='1' cellpadding='5' cellspacing='0'>";
echo "<tr><th>Cryptocurrency</th><th>Symbol</th><th>Last Close Price</th><th>All-Time High</th><th>Correction Range (%)</th></tr>";

// Iterate over each cryptocurrency
foreach ($cryptocurrencies as $crypto) {
    $symbol = $crypto['symbol'];
    $name = $crypto['name'];
    $url = "{$baseUrl}{$symbol}USD/range/1/day/{$startDate}/{$endDate}?adjusted=true&sort=asc&apiKey={$apiKey}";

    // Initialize a cURL session
    $ch = curl_init();

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute cURL request
    $response = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        echo "<tr><td colspan='5'>Error fetching data for {$name} ({$symbol}): " . curl_error($ch) . "</td></tr>";
        curl_close($ch);
        continue; // Skip this cryptocurrency if an error occurs
    }

    // Close cURL session
    curl_close($ch);

    // Decode JSON response
    $data = json_decode($response, true);

    // Initialize variables to store the last close price and the all-time high
    $lastClosePrice = null;
    $allTimeHigh = null;

    // Check if data is available
    if (isset($data['results']) && is_array($data['results'])) {
        $results = $data['results'];
        
        // Get the last close price
        $lastResult = end($results);
        $lastClosePrice = $lastResult['c'];

        // Calculate the all-time high
        foreach ($results as $dayData) {
            if ($allTimeHigh === null || $dayData['h'] > $allTimeHigh) {
                $allTimeHigh = $dayData['h'];
            }
        }

        // Calculate the correction range as a percentage
        $correctionRange = null;
        if ($allTimeHigh > 0) {
            $correctionRange = (($allTimeHigh - $lastClosePrice) / $allTimeHigh) * 100;
        }

        // Output the results for the current cryptocurrency in table rows
        echo "<tr>";
        echo "<td>{$name}</td>";
        echo "<td>{$url}</td>";
        echo "<td>" . number_format($lastClosePrice, 2) . "</td>";
        echo "<td>" . number_format($allTimeHigh, 2) . "</td>";
        echo "<td>" . number_format($correctionRange, 2) . "%</td>";
        echo "</tr>";

        // Prepare the SQL query to update the data
        $stmt = $conn->prepare("
            UPDATE crypto
            SET last_close_price = ?, all_time_high = ?, correction_range = ?
            WHERE symbol = ?
        ");

        // Bind parameters to the SQL query
        $stmt->bind_param('ddds', $lastClosePrice, $allTimeHigh, $correctionRange, $symbol);

        // Execute the query
        if (!$stmt->execute()) {
            echo "<tr><td colspan='5'>Error updating data for {$name} ({$symbol}): " . $stmt->error . "</td></tr>";
        }

        // Close the statement
        $stmt->close();

    } else {
        echo "<tr><td colspan='5'>Error fetching data for {$name} ({$symbol}): Invalid data received.</td></tr>";
    }

    
}

// Close the database connection
$conn->close();

// End HTML table
echo "</table>";

?>
