<?php
require_once 'config.php';
require_once 'functions.php';
$date=date('Y-m-d');

$companies= [
    'A' => 'Agilent Technologies',
    'AAL' => 'American Airlines Group',
    'AAPL' => 'Apple Inc.',
    'ABBV' => 'AbbVie',
    'ABNB' => 'Airbnb',
    'ABT' => 'Abbott',
    'ACGL' => 'Arch Capital Group',
    'ACN' => 'Accenture',
    'ADBE' => 'Adobe Inc.',
    'ADI' => 'Analog Devices',
    'ADM' => 'Archer-Daniels-Midland',
    'ADP' => 'Automatic Data Processing',
    'ADSK' => 'Autodesk',
    'AEE' => 'Ameren',
    'AEP' => 'American Electric Power',
    'AES' => 'AES Corporation',
    'AFL' => 'Aflac',
    'AIG' => 'American International Group',
    'AIZ' => 'Assurant',
    'AJG' => 'Arthur J. Gallagher & Co.',
    'AKAM' => 'Akamai',
    'ALB' => 'Albemarle Corporation',
    'ALGN' => 'Align Technology',
    'ALL' => 'Allstate',
    'ALLE' => 'Allegion',
    'AMAT' => 'Applied Materials',
    'AMCR' => 'Amcor',
    'AMD' => 'Advanced Micro Devices',
    'AME' => 'Ametek',
    'AMGN' => 'Amgen',
    'AMP' => 'Ameriprise Financial',
    'AMT' => 'American Tower',
    'AMZN' => 'Amazon',
    'ANET' => 'Arista Networks',
    'ANSS' => 'Ansys',
    'AON' => 'Aon',
    'AOS' => 'A. O. Smith',
    'APA' => 'APA Corporation',
    'APD' => 'Air Products and Chemicals',
    'APH' => 'Amphenol',
    'APTV' => 'Aptiv',
    'ARE' => 'Alexandria Real Estate Equities',
    'ATO' => 'Atmos Energy',
    'AVB' => 'AvalonBay Communities',
    'AVGO' => 'Broadcom Inc.',
    'AVY' => 'Avery Dennison',
    'AWK' => 'American Water Works',
    'AXON' => 'Axon Enterprise',
    'AXP' => 'American Express',
    'AZO' => 'AutoZone',
    'BA' => 'Boeing',
    'BAC' => 'Bank of America',
    'BALL' => 'Ball Corporation',
    'BAX' => 'Baxter International',
    'BBWI' => 'Bath & Body Works, Inc.',
    'BBY' => 'Best Buy',
    'BDX' => 'Becton Dickinson',
    'BEN' => 'Franklin Templeton',
    'BF.B' => 'Brown–Forman',
    'BG' => 'Bunge Global SA',
    'BIIB' => 'Biogen',
    'BIO' => 'Bio-Rad',
    'BK' => 'Bank of New York Mellon',
    'BKNG' => 'Booking Holdings',
    'BKR' => 'Baker Hughes',
    'BLDR' => 'Builders FirstSource',
    'BLK' => 'BlackRock',
    'BMY' => 'Bristol Myers Squibb',
    'BR' => 'Broadridge Financial Solutions',
    'BRK.B' => 'Berkshire Hathaway',
    'BRO' => 'Brown & Brown',
    'BSX' => 'Boston Scientific',
    'BWA' => 'BorgWarner',
    'BX' => 'Blackstone',
    'BXP' => 'Boston Properties',
    'C' => 'Citigroup',
    'CAG' => 'Conagra Brands',
    'CAH' => 'Cardinal Health',
    'CARR' => 'Carrier Global',
    'CAT' => 'Caterpillar Inc.',
    'CB' => 'Chubb Limited',
    'CBOE' => 'Cboe Global Markets',
    'CBRE' => 'CBRE Group',
    'CCI' => 'Crown Castle',
    'CCL' => 'Carnival',
    'CDNS' => 'Cadence Design Systems',
    'CDW' => 'CDW',
    'CE' => 'Celanese',
    'CEG' => 'Constellation Energy',
    'CF' => 'CF Industries',
    'CFG' => 'Citizens Financial Group',
    'CHD' => 'Church & Dwight',
    'CHRW' => 'CH Robinson',
    'CHTR' => 'Charter Communications',
    'CI' => 'Cigna'
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
