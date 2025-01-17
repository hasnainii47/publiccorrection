<?php
require_once 'config.php';
require_once 'functions.php';
$date=date('Y-m-d');
$companies = [
    'MA' => 'Mastercard',
    'MAA' => 'Mid-America Apartment Communities',
    'MAR' => 'Marriott International',
    'MAS' => 'Masco',
    'MCD' => 'McDonald\'s',
    'MCHP' => 'Microchip Technology',
    'MCK' => 'McKesson Corporation',
    'MCO' => 'Moody\'s Corporation',
    'MDLZ' => 'Mondelez International',
    'MDT' => 'Medtronic',
    'MET' => 'MetLife',
    'META' => 'Meta Platforms',
    'MGM' => 'MGM Resorts',
    'MHK' => 'Mohawk Industries',
    'MKC' => 'McCormick & Company',
    'MKTX' => 'MarketAxess',
    'MLM' => 'Martin Marietta Materials',
    'MMC' => 'Marsh McLennan',
    'MMM' => '3M',
    'MNST' => 'Monster Beverage',
    'MO' => 'Altria',
    'MOH' => 'Molina Healthcare',
    'MOS' => 'Mosaic Company (The)',
    'MPC' => 'Marathon Petroleum',
    'MPWR' => 'Monolithic Power Systems',
    'MRK' => 'Merck & Co.',
    'MRNA' => 'Moderna',
    'MRO' => 'Marathon Oil',
    'MS' => 'Morgan Stanley',
    'MSCI' => 'MSCI',
    'MSFT' => 'Microsoft',
    'MSI' => 'Motorola Solutions',
    'MTB' => 'M&T Bank',
    'MTCH' => 'Match Group',
    'MTD' => 'Mettler Toledo',
    'MU' => 'Micron Technology',
    'NCLH' => 'Norwegian Cruise Line Holdings',
    'NDAQ' => 'Nasdaq, Inc.',
    'NDSN' => 'Nordson Corporation',
    'NEE' => 'NextEra Energy',
    'NEM' => 'Newmont',
    'NFLX' => 'Netflix',
    'NI' => 'NiSource',
    'NKE' => 'Nike, Inc.',
    'NOC' => 'Northrop Grumman',
    'NOW' => 'ServiceNow',
    'NRG' => 'NRG Energy',
    'NSC' => 'Norfolk Southern Railway',
    'NTAP' => 'NetApp',
    'NTRS' => 'Northern Trust',
    'NUE' => 'Nucor',
    'NVDA' => 'Nvidia',
    'NVR' => 'NVR, Inc.',
    'NWS' => 'News Corp (Class B)',
    'NWSA' => 'News Corp (Class A)',
    'NXPI' => 'NXP Semiconductors',
    'O' => 'Realty Income',
    'ODFL' => 'Old Dominion',
    'OKE' => 'ONEOK',
    'OMC' => 'Omnicom Group',
    'ON' => 'ON Semiconductor',
    'ORCL' => 'Oracle Corporation',
    'ORLY' => 'O\'Reilly Auto Parts',
    'OTIS' => 'Otis Worldwide',
    'OXY' => 'Occidental Petroleum',
    'PANW' => 'Palo Alto Networks',
    'PARA' => 'Paramount Global',
    'PAYC' => 'Paycom',
    'PAYX' => 'Paychex',
    'PCAR' => 'Paccar',
    'PCG' => 'PG&E Corporation',
    'PEG' => 'Public Service Enterprise Group',
    'PEP' => 'PepsiCo',
    'PFE' => 'Pfizer',
    'PFG' => 'Principal Financial Group',
    'PG' => 'Procter & Gamble',
    'PGR' => 'Progressive Corporation',
    'PH' => 'Parker Hannifin',
    'PHM' => 'PulteGroup',
    'PKG' => 'Packaging Corporation of America',
    'PLD' => 'Prologis',
    'PM' => 'Philip Morris International',
    'PNC' => 'PNC Financial Services',
    'PNR' => 'Pentair',
    'PNW' => 'Pinnacle West',
    'PODD' => 'Insulet Corporation',
    'POOL' => 'Pool Corporation',
    'PPG' => 'PPG Industries',
    'PPL' => 'PPL Corporation',
    'PRU' => 'Prudential Financial',
    'PSA' => 'Public Storage',
    'PSX' => 'Phillips 66',
    'PTC' => 'PTC',
    'PWR' => 'Quanta Services',
    'PYPL' => 'PayPal'
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
        if ($symbol=='MSFT') {
            $marketCap='3180000000000';
        }

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
