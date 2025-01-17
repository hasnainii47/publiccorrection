<?php
require_once 'config.php';
require_once 'functions.php';
$date=date('Y-m-d');
$companies = [
    'QCOM' => 'Qualcomm',
    'QRVO' => 'Qorvo',
    'RCL' => 'Royal Caribbean Group',
    'REG' => 'Regency Centers',
    'REGN' => 'Regeneron',
    'RF' => 'Regions Financial Corporation',
    'RHI' => 'Robert Half',
    'RJF' => 'Raymond James',
    'RL' => 'Ralph Lauren Corporation',
    'RMD' => 'ResMed',
    'ROK' => 'Rockwell Automation',
    'ROL' => 'Rollins, Inc.',
    'ROP' => 'Roper Technologies',
    'ROST' => 'Ross Stores',
    'RSG' => 'Republic Services',
    'RTX' => 'RTX Corporation',
    'RVTY' => 'Revvity',
    'SBAC' => 'SBA Communications',
    'SBUX' => 'Starbucks',
    'SCHW' => 'Charles Schwab Corporation',
    'SHW' => 'Sherwin-Williams',
    'SJM' => 'J.M. Smucker Company (The)',
    'SLB' => 'Schlumberger',
    'SMCI' => 'Supermicro',
    'SNA' => 'Snap-on',
    'SNPS' => 'Synopsys',
    'SO' => 'Southern Company',
    'SOLV' => 'Solventum',
    'SPG' => 'Simon Property Group',
    'SPGI' => 'S&P Global',
    'SRE' => 'Sempra Energy',
    'STE' => 'Steris',
    'STLD' => 'Steel Dynamics',
    'STT' => 'State Street Corporation',
    'STX' => 'Seagate Technology',
    'STZ' => 'Constellation Brands',
    'SWK' => 'Stanley Black & Decker',
    'SWKS' => 'Skyworks Solutions',
    'SYF' => 'Synchrony Financial',
    'SYK' => 'Stryker Corporation',
    'SYY' => 'Sysco',
    'T' => 'AT&T',
    'TAP' => 'Molson Coors Beverage Company',
    'TDG' => 'TransDigm Group',
    'TDY' => 'Teledyne Technologies',
    'TECH' => 'Bio-Techne',
    'TEL' => 'TE Connectivity',
    'TER' => 'Teradyne',
    'TFC' => 'Truist',
    'TFX' => 'Teleflex',
    'TGT' => 'Target Corporation',
    'TJX' => 'TJX Companies',
    'TMO' => 'Thermo Fisher Scientific',
    'TMUS' => 'T-Mobile US',
    'TPR' => 'Tapestry, Inc.',
    'TRGP' => 'Targa Resources',
    'TRMB' => 'Trimble Inc.',
    'TROW' => 'T. Rowe Price',
    'TRV' => 'Travelers Companies (The)',
    'TSCO' => 'Tractor Supply',
    'TSLA' => 'Tesla, Inc.',
    'TSN' => 'Tyson Foods',
    'TT' => 'Trane Technologies',
    'TTWO' => 'Take-Two Interactive',
    'TXN' => 'Texas Instruments',
    'TXT' => 'Textron',
    'TYL' => 'Tyler Technologies',
    'UAL' => 'United Airlines Holdings',
    'UBER' => 'Uber',
    'UDR' => 'UDR, Inc.',
    'UHS' => 'Universal Health Services',
    'ULTA' => 'Ulta Beauty',
    'UNH' => 'UnitedHealth Group',
    'UNP' => 'Union Pacific Corporation',
    'UPS' => 'United Parcel Service',
    'URI' => 'United Rentals',
    'USB' => 'U.S. Bank',
    'V' => 'Visa Inc.',
    'VICI' => 'Vici Properties',
    'VLO' => 'Valero Energy',
    'VLTO' => 'Veralto',
    'VMC' => 'Vulcan Materials Company',
    'VRSK' => 'Verisk',
    'VRSN' => 'Verisign',
    'VRTX' => 'Vertex Pharmaceuticals',
    'VST' => 'Vistra',
    'VTR' => 'Ventas',
    'VTRS' => 'Viatris',
    'VZ' => 'Verizon',
    'WAB' => 'Wabtec',
    'WAT' => 'Waters Corporation',
    'WBA' => 'Walgreens Boots Alliance',
    'WBD' => 'Warner Bros. Discovery',
    'WDC' => 'Western Digital',
    'WEC' => 'WEC Energy Group',
    'WELL' => 'Welltower',
    'WFC' => 'Wells Fargo',
    'WM' => 'Waste Management',
    'WMB' => 'Williams Companies',
    'WMT' => 'Walmart',
    'WRB' => 'W. R. Berkley Corporation',
    'WRK' => 'WestRock',
    'WST' => 'West Pharmaceutical Services',
    'WTW' => 'Willis Towers Watson',
    'WY' => 'Weyerhaeuser',
    'WYNN' => 'Wynn Resorts',
    'XEL' => 'Xcel Energy',
    'XOM' => 'ExxonMobil',
    'XYL' => 'Xylem Inc.',
    'YUM' => 'Yum! Brands',
    'ZBH' => 'Zimmer Biomet',
    'ZBRA' => 'Zebra Technologies',
    'ZTS' => 'Zoetis'
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
