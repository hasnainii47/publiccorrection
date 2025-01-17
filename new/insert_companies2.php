<?php
require_once 'config.php';
require_once 'functions.php';
$date=date('Y-m-d');
$companies = [
    'CINF' => 'Cincinnati Financial',
    'CL' => 'Colgate-Palmolive',
    'CLX' => 'Clorox',
    'CMA' => 'Comerica',
    'CMCSA' => 'Comcast',
    'CME' => 'CME Group',
    'CMG' => 'Chipotle Mexican Grill',
    'CMI' => 'Cummins',
    'CMS' => 'CMS Energy',
    'CNC' => 'Centene Corporation',
    'CNP' => 'CenterPoint Energy',
    'COF' => 'Capital One',
    'COO' => 'CooperCompanies',
    'COP' => 'ConocoPhillips',
    'COR' => 'Cencora',
    'COST' => 'Costco',
    'CPAY' => 'Corpay',
    'CPB' => 'Campbell Soup Company',
    'CPRT' => 'Copart',
    'CPT' => 'Camden Property Trust',
    'CRL' => 'Charles River Laboratories',
    'CRM' => 'Salesforce',
    'CSCO' => 'Cisco',
    'CSGP' => 'CoStar Group',
    'CSX' => 'CSX',
    'CTAS' => 'Cintas',
    'CTLT' => 'Catalent',
    'CTRA' => 'Coterra',
    'CTSH' => 'Cognizant',
    'CTVA' => 'Corteva',
    'CVS' => 'CVS Health',
    'CVX' => 'Chevron Corporation',
    'CZR' => 'Caesars Entertainment',
    'D' => 'Dominion Energy',
    'DAL' => 'Delta Air Lines',
    'DAY' => 'Dayforce',
    'DD' => 'DuPont',
    'DE' => 'John Deere',
    'DECK' => 'Deckers Brands',
    'DFS' => 'Discover Financial',
    'DG' => 'Dollar General',
    'DGX' => 'Quest Diagnostics',
    'DHI' => 'DR Horton',
    'DHR' => 'Danaher Corporation',
    'DIS' => 'Walt Disney Company (The)',
    'DLR' => 'Digital Realty',
    'DLTR' => 'Dollar Tree',
    'DOC' => 'Healthpeak',
    'DOV' => 'Dover Corporation',
    'DOW' => 'Dow Inc.',
    'DPZ' => 'Domino\'s',
    'DRI' => 'Darden Restaurants',
    'DTE' => 'DTE Energy',
    'DUK' => 'Duke Energy',
    'DVA' => 'DaVita Inc.',
    'DVN' => 'Devon Energy',
    'DXCM' => 'Dexcom',
    'EA' => 'Electronic Arts',
    'EBAY' => 'eBay',
    'ECL' => 'Ecolab',
    'ED' => 'Consolidated Edison',
    'EFX' => 'Equifax',
    'EG' => 'Everest Re',
    'EIX' => 'Edison International',
    'EL' => 'Estée Lauder Companies (The)',
    'ELV' => 'Elevance Health',
    'EMN' => 'Eastman Chemical Company',
    'EMR' => 'Emerson Electric',
    'ENPH' => 'Enphase',
    'EOG' => 'EOG Resources',
    'EPAM' => 'EPAM Systems',
    'EQIX' => 'Equinix',
    'EQR' => 'Equity Residential',
    'EQT' => 'EQT Corporation',
    'ES' => 'Eversource',
    'ESS' => 'Essex Property Trust',
    'ETN' => 'Eaton Corporation',
    'ETR' => 'Entergy',
    'ETSY' => 'Etsy',
    'EVRG' => 'Evergy',
    'EW' => 'Edwards Lifesciences',
    'EXC' => 'Exelon',
    'EXPD' => 'Expeditors International',
    'EXPE' => 'Expedia Group',
    'EXR' => 'Extra Space Storage',
    'F' => 'Ford Motor Company',
    'FANG' => 'Diamondback Energy',
    'FAST' => 'Fastenal',
    'FCX' => 'Freeport-McMoRan',
    'FDS' => 'FactSet',
    'FDX' => 'FedEx',
    'FE' => 'FirstEnergy',
    'FFIV' => 'F5, Inc.',
    'FI' => 'Fiserv'
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
