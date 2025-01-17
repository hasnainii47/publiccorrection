<?php

// Include the configuration file
require_once 'config.php';

// Function to get data from Polygon API
function getApiData($symbol, $startDate, $endDate) {
    // Construct the API URL for the given symbol with dynamic dates
    $api_url = "https://api.polygon.io/v2/aggs/ticker/I:$symbol/range/1/day/$startDate/$endDate?sort=asc&apiKey=" . API_KEY;

    // Initialize cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

// Array of indexes to update
$indexes = [
    "000001" => "SSE Composite Index",
    "HSI" => "Hang Seng Index",
    "0142" => "First Pacific Co Ltd",
    "0137" => "Aldeyra Therapeutics Inc"
];

// Calculate current date and five years ago date
$currentDate = date('Y-m-d'); // Current date in 'YYYY-MM-DD' format
$fiveYearsAgoDate = date('Y-m-d', strtotime('-5 years')); // Date 5 years ago in 'YYYY-MM-DD' format

// Create a connection to the MySQL database
$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check the connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Loop through each index and update the database
foreach ($indexes as $symbol => $name) {
    // Get data from the API using dynamic dates
    $json_response = getApiData($symbol, $fiveYearsAgoDate, $currentDate);

    // Decode the JSON response into an associative array
    $data = json_decode($json_response, true);

    if (isset($data['results']) && count($data['results']) > 0) {
        // Extract the last closing price
        $last_close = $data['results'][count($data['results']) - 1]['c'];

        // Calculate the all-time high
        $all_time_high = 0;
        foreach ($data['results'] as $result) {
            if ($result['h'] > $all_time_high) {
                $all_time_high = $result['h'];
            }
        }

        // Prepare an SQL statement to update the record
        $sql = "UPDATE `indexes` SET `last_close` = ?, `all_time_high` = ? WHERE `symbl` = ?";

        // Prepare and bind
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("dds", $last_close, $all_time_high, $symbol);

        // Execute the statement
        if ($stmt->execute()) {
            echo "Record updated successfully for $symbol ($name).<br>";
        } else {
            echo "Error updating record for $symbol ($name): " . $stmt->error . "<br>";
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "No data found for $symbol ($name).<br>";
    }
}

// Close the connection
$connection->close();

?>
