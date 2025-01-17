<?php
require_once 'config.php';
require_once 'functions.php';
$date=date('Y-m-d');
$companies = [
    'FICO' => 'Fair Isaac',
    'FIS' => 'Fidelity National Information Services',
    'FITB' => 'Fifth Third Bank',
    'FMC' => 'FMC Corporation',
    'FOX' => 'Fox Corporation (Class B)',
    'FOXA' => 'Fox Corporation (Class A)',
    'FRT' => 'Federal Realty',
    'FSLR' => 'First Solar',
    'FTNT' => 'Fortinet',
    'FTV' => 'Fortive',
    'GD' => 'General Dynamics',
    'GE' => 'GE Aerospace',
    'GEHC' => 'GE HealthCare',
    'GEN' => 'Gen Digital',
    'GEV' => 'GE Vernova',
    'GILD' => 'Gilead Sciences',
    'GIS' => 'General Mills',
    'GL' => 'Globe Life',
    'GLW' => 'Corning Inc.',
    'GM' => 'General Motors',
    'GNRC' => 'Generac',
    'GOOG' => 'Alphabet Inc. (Class C)',
    'GOOGL' => 'Alphabet Inc. (Class A)',
    'GPC' => 'Genuine Parts Company',
    'GPN' => 'Global Payments',
    'GRMN' => 'Garmin',
    'GS' => 'Goldman Sachs',
    'GWW' => 'W. W. Grainger',
    'HAL' => 'Halliburton',
    'HAS' => 'Hasbro',
    'HBAN' => 'Huntington Bancshares',
    'HCA' => 'HCA Healthcare',
    'HD' => 'Home Depot (The)',
    'HES' => 'Hess Corporation',
    'HIG' => 'Hartford (The)',
    'HII' => 'Huntington Ingalls Industries',
    'HLT' => 'Hilton Worldwide',
    'HOLX' => 'Hologic',
    'HON' => 'Honeywell',
    'HPE' => 'Hewlett Packard Enterprise',
    'HPQ' => 'HP Inc.',
    'HRL' => 'Hormel Foods',
    'HSIC' => 'Henry Schein',
    'HST' => 'Host Hotels & Resorts',
    'HSY' => 'Hershey\'s',
    'HUBB' => 'Hubbell Incorporated',
    'HUM' => 'Humana',
    'HWM' => 'Howmet Aerospace',
    'IBM' => 'IBM',
    'ICE' => 'Intercontinental Exchange',
    'IDXX' => 'Idexx Laboratories',
    'IEX' => 'IDEX Corporation',
    'IFF' => 'International Flavors & Fragrances',
    'ILMN' => 'Illumina',
    'INCY' => 'Incyte',
    'INTC' => 'Intel',
    'INTU' => 'Intuit',
    'INVH' => 'Invitation Homes',
    'IP' => 'International Paper',
    'IPG' => 'Interpublic Group of Companies (The)',
    'IQV' => 'IQVIA',
    'IR' => 'Ingersoll Rand',
    'IRM' => 'Iron Mountain',
    'ISRG' => 'Intuitive Surgical',
    'IT' => 'Gartner',
    'ITW' => 'Illinois Tool Works',
    'IVZ' => 'Invesco',
    'J' => 'Jacobs Solutions',
    'JBHT' => 'J.B. Hunt',
    'JBL' => 'Jabil',
    'JCI' => 'Johnson Controls',
    'JKHY' => 'Jack Henry & Associates',
    'JNJ' => 'Johnson & Johnson',
    'JNPR' => 'Juniper Networks',
    'JPM' => 'JPMorgan Chase',
    'K' => 'Kellanova',
    'KDP' => 'Keurig Dr Pepper',
    'KEY' => 'KeyCorp',
    'KEYS' => 'Keysight',
    'KHC' => 'Kraft Heinz',
    'KIM' => 'Kimco Realty',
    'KLAC' => 'KLA Corporation',
    'KMB' => 'Kimberly-Clark',
    'KMI' => 'Kinder Morgan',
    'KMX' => 'CarMax',
    'KO' => 'Coca-Cola Company (The)',
    'KR' => 'Kroger',
    'KVUE' => 'Kenvue',
    'L' => 'Loews Corporation',
    'LDOS' => 'Leidos',
    'LEN' => 'Lennar',
    'LH' => 'LabCorp',
    'LHX' => 'L3Harris',
    'LIN' => 'Linde plc',
    'LKQ' => 'LKQ Corporation',
    'LLY' => 'Eli Lilly and Company',
    'LMT' => 'Lockheed Martin',
    'LNT' => 'Alliant Energy',
    'LOW' => 'Lowe\'s',
    'LRCX' => 'Lam Research',
    'LULU' => 'Lululemon Athletica',
    'LUV' => 'Southwest Airlines',
    'LVS' => 'Las Vegas Sands',
    'LW' => 'Lamb Weston',
    'LYB' => 'LyondellBasell',
    'LYV' => 'Live Nation Entertainment'
];

$con = getDbConnection();

foreach ($companies as $symbol => $name) {
    try {
        // Fetch last close price
         // First API call to get last close price for a specific date
        $lastCloseUrl = "https://api.polygon.io/v2/aggs/ticker/$symbol/range/1/day/$date/$date?apiKey=" . API_KEY;
        $responseLastClose = file_get_contents($lastCloseUrl);
        if ($responseLastClose === false) {
            throw new Exception("Error fetching last close data for $symbol on $date");
        }
        $lastCloseData = json_decode($responseLastClose, true);
        $lastClosePrice = $lastCloseData['results'][0]['c'] ?? null;

        // If last close price is null, make a second API call to get previous close price
        if ($lastClosePrice === null) {
            $lastCloseUrl = "https://api.polygon.io/v2/aggs/ticker/$symbol/prev?adjusted=true&apiKey=" . API_KEY;
            $responseLastClose = file_get_contents($lastCloseUrl);
            if ($responseLastClose === false) {
                throw new Exception("Error fetching previous close data for $symbol");
            }
            $lastCloseData = json_decode($responseLastClose, true);
            $lastClosePrice = $lastCloseData['results'][0]['c'] ?? null;
        }

        if ($lastClosePrice === null) {
            throw new Exception("Unable to retrieve last close price for $symbol");
        }

        // Fetch all-time high
        $allTimeHighUrl = "https://api.polygon.io/v2/aggs/ticker/$symbol/range/1/day/2010-01-01/2024-12-31?adjusted=true&apiKey=" . API_KEY;
        $responseAllTimeHigh = file_get_contents($allTimeHighUrl);
        if ($responseAllTimeHigh === false) {
            throw new Exception("Error fetching all time high data for $symbol");
        }
        $allTimeHighData = json_decode($responseAllTimeHigh, true);
        $allTimeHighPrice = max(array_column($allTimeHighData['results'], 'h'));

        // Calculate correction range
        $correctionRange = ($lastClosePrice - $allTimeHighPrice) / $allTimeHighPrice * 100;
        $correctionRangeFormatted = formatCorrectionRange($correctionRange);

        // Fetch market cap
        $companyUrl = "https://api.polygon.io/v3/reference/tickers/$symbol?apiKey=" . API_KEY;
        $responseCompany = file_get_contents($companyUrl);
        if ($responseCompany === false) {
            throw new Exception("Error fetching company data for $symbol");
        }
        $companyData = json_decode($responseCompany, true);
        $marketCap = $companyData['results']['market_cap'] ?? null;

        // Fetch average volume for May 2024
        $volumeUrl = "https://api.polygon.io/v2/aggs/ticker/$symbol/range/1/day/2024-05-01/2024-05-31?adjusted=true&apiKey=" . API_KEY;
        $responseVolume = file_get_contents($volumeUrl);
        if ($responseVolume === false) {
            throw new Exception("Error fetching volume data for $symbol");
        }
        $volumeData = json_decode($responseVolume, true);
        $averageVolume = array_sum(array_column($volumeData['results'], 'v')) / count($volumeData['results']);

        // Attempt to update existing record; if no rows affected, then insert new record
        $dateTime = date('Y-m-d H:i:s');
        $snapshotUrl = "https://api.polygon.io/v2/snapshot/locale/us/markets/stocks/tickers/$symbol?apiKey=" . API_KEY;
        $responseSnapshot = file_get_contents($snapshotUrl);
       
        if ($responseSnapshot === false) {
            throw new Exception("Error fetching snapshot data for $symbol");
        }
        $snapshotData = json_decode($responseSnapshot, true);
        $todaysChangePerc = $snapshotData['ticker']['todaysChangePerc'] ?? null;

        // Convert todaysChangePerc to string if it's not null
        if ($todaysChangePerc !== null) {
            $todaysChangePerc = number_format($todaysChangePerc, 2);
        } else {
            $todaysChangePerc = 'N/A'; // Handle null cases if API doesn't return data
        }


        

        $updateStmt = $con->prepare("UPDATE companies SET last_close=?, all_time_high=?, market_cap=?, average_volume=?, correction_range=?, date_time=?,todaysChangePerc=? WHERE symbl=?");
        if (!$updateStmt) {
            throw new Exception("Prepare statement failed: " . $con->error);
        }
        $updateStmt->bind_param("dddidsss", $lastClosePrice, $allTimeHighPrice, $marketCap, $averageVolume, $correctionRangeFormatted, $dateTime,$todaysChangePerc, $symbol);
        
        if (!$updateStmt->execute()) {
            // If no rows updated (record doesn't exist), insert new record
            if ($updateStmt->errno === 0 && $updateStmt->affected_rows === 0) {
                $insertStmt = $con->prepare("INSERT INTO companies (symbl, name, last_close, all_time_high, market_cap, average_volume, correction_range, date_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                if (!$insertStmt) {
                    throw new Exception("Prepare statement failed: " . $con->error);
                }
                $insertStmt->bind_param("ssdddids", $symbol, $name, $lastClosePrice, $allTimeHighPrice, $marketCap, $averageVolume, $correctionRangeFormatted, $dateTime);
                if (!$insertStmt->execute()) {
                    throw new Exception("Execute failed: " . $insertStmt->error);
                }
                echo "Record for $name inserted successfully.\n";
                $insertStmt->close();
            } else {
                throw new Exception("Execute failed: " . $updateStmt->error);
            }
        } else {
            echo "Record for $name updated successfully.\n";
        }
        $updateStmt->close();
    } catch (Exception $e) {
        echo "Error processing $name: " . $e->getMessage() . "\n";
    }
}

$con->close();

// Function to format correction range
function formatCorrectionRange($range) {
    return ($range >= 0) ? '+' . number_format($range, 2) . '%' : number_format($range, 2) . '%';
}
?>
