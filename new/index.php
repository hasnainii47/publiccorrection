<?php
$con = new mysqli('localhost', 'corrgltt_companies', '?%!!mZ5HP^#-', 'corrgltt_companies');
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Test</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
    <style>
        .bg-warning {
            background-color: #f5be19 !important;
        }
        .bg-danger, .bg-success, .bg-warning {
            color: white !important;
            font-weight: 500;
        }
        thead {
            font-size: 13px;
        }
        table {
            text-align: center;
        }
        .cname {
            text-align: left;
        }
        .table-striped tbody tr:nth-of-type(odd) {
                background-color: #E6E6B1;
            }
        table.dataTable tbody tr {
            background-color: #FFFFC5;
        }
        table.dataTable thead th, table.dataTable thead td {
                padding: 9px 2px;
                border-bottom: 1px solid #111;
            }
        
        table.dataTable thead .sorting {
            background-image:none;
        }    
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #FFFFC5;
        }  
          
            
        @media (max-width: 768px) {
            .hidemobile {
                display: none;
            }
            thead, table {
                font-size: 10px;
            }
            
        }


    </style>
    <script>
        function sendHeightToParent() {
            var height = document.body.scrollHeight;
            window.parent.postMessage({ height: height }, '*');
        }

        window.onload = sendHeightToParent;
        window.onresize = sendHeightToParent;
    </script>
</head>
<body>
    <div class="container">
        <h3 class="mb-4">All S&P 500 stocks and their current correction territory categories</h3>
        <p class="text-muted">Last Updated:
            <?php
                date_default_timezone_set('America/New_York'); // Set the timezone to New York

                $current_time = strtotime(date('H:i')); // Get the current time in hours and minutes
                $threshold_time = strtotime('16:30'); // 4:30 PM in 24-hour format

                // Check if the current time is before 4:30 PM
                if ($current_time < $threshold_time) {
                    $last_updated = date('Y-m-d', strtotime('-1 day')); // Subtract 1 day from current date
                } else {
                    $last_updated = date('Y-m-d'); // Use current date
                }
                echo $last_updated.',  4:30 PM';
                ?>
        </p>

        <div class="row">
            <div class="col-md-12">
                <div class="mb-3">
                    <select id="companySelect" class="selectpicker" data-show-subtext="true" data-live-search="true">
                        <option selected disabled>Find a company</option>
                        <?php
                        $categories_query = mysqli_query($con, "SELECT * FROM companies");
                        while ($fetch = mysqli_fetch_array($categories_query)) { ?>
                            <option value="<?php echo $fetch["name"]; ?>"><?php echo $fetch["name"]; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="filterOption" id="filterOption1" value="-10">
                        <label class="form-check-label" for="filterOption1">Stocks down more than 10%</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="filterOption" id="filterOption2" value="-20">
                        <label class="form-check-label" for="filterOption2">More than 20%</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="filterOption" id="filterOption3" value="-30">
                        <label class="form-check-label" for="filterOption3">More than 30%</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="filterOption" id="filterOption4" value="-40">
                        <label class="form-check-label" for="filterOption4">More than 40%</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="filterOption" id="filterOption5" value="magnificentSeven">
                        <label class="form-check-label" for="filterOption5">Magnificent Seven</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table id="stockTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="cname" data-column="name" width="25%">Company Name</th>
                        <th data-column="lastClosePrice">Last Close Price ($)</th>
                        <th data-column="allTimeHighPrice">All Time High ($)</th>
                        <th data-column="marketCap">Market Cap</th>
                        <th class="hidemobile" data-column="averageVolume">Average Volume</th>
                        <th data-column="correctionRange">Correction Range (%)</th>
                    </tr>
                </thead>
                <tbody id="stockTableBody">
                    <?php
                    function convertVolume($volume) {
                        $billions = $volume / 1_000_000_000;
                        $billions_rounded = round($billions, 2);
                        $millions = $volume / 1_000_000;
                        $millions_rounded = round($millions, 2);
                        return [
                            'billions' => $billions_rounded . ' B',
                            'millions' => $millions_rounded . ' M'
                        ];
                    }

                    $categories_query = mysqli_query($con, "SELECT * FROM companies where market_cap>0");
                    while ($fetch_categories = mysqli_fetch_array($categories_query)) {
                        $correctionRange = $fetch_categories["correction_range"];
                        $market_cap = $fetch_categories["market_cap"];
                        $average_volume = $fetch_categories["average_volume"];
                        $converted_market_cap = convertVolume($market_cap);
                        $converted_average_volume = convertVolume($average_volume);
                        $exchange_value=$fetch_categories["exchange_name"];
                        if ($exchange_value=='XNAS') {
                           $exchange_name="NASDAQ";
                        }
                        elseif ($exchange_value=='XNYS') {
                            $exchange_name="NYSE";
                        }
                    ?>
                        <tr>
                            <td class="cname"><a href="https://www.google.com/finance/quote/<?php echo $fetch_categories["symbl"]; ?>:<?php echo $exchange_name; ?>?hl=en&window=1Y" target="_blank"><?php echo $fetch_categories["name"]; ?></a></td>
                            <td><?php echo $fetch_categories["last_close"]; ?></td>
                            <td><?php echo $fetch_categories["all_time_high"]; ?></td>
                            <td><?php echo $converted_market_cap['billions']; ?></td>
                            <td  class="hidemobile"><?php echo $converted_average_volume['millions']; ?></td>
                            <td class="<?php if ($correctionRange >= -5) { echo 'bg-danger'; } else if ($correctionRange >= -15) { echo 'bg-warning'; } else { echo 'bg-success'; } ?>"><?php echo $fetch_categories["correction_range"]; ?></td>
                        </tr>
                    <?php }  $con->close(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('.selectpicker').selectpicker();
            $('#stockTable').DataTable({
                searching: false,
                paging: false,
                info: false
            });

            $('input[name="filterOption"]').on('change', function() {
                filterTable(this.value);
            });

            $('#companySelect').on('change', function() {
                filterTableByCompany(this.value);
            });

            function filterTable(filterValue) {
                let rows = $('#stockTableBody tr');
                rows.each(function() {
                    let correctionRange = parseFloat($(this).find('td:eq(5)').text());
                    if (filterValue === 'magnificentSeven') {
                        let magnificentSeven = ['Apple Inc.', 'Alphabet Inc.', 'Microsoft Corporation', 'Amazon.com Inc.', 'Meta Platforms Inc.', 'Tesla, Inc.', 'NVIDIA Corporation'];
                        if (magnificentSeven.includes($(this).find('td:eq(0)').text())) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    } else {
                        if (correctionRange <= parseFloat(filterValue)) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    }
                });
            }

            function filterTableByCompany(companyName) {
                let rows = $('#stockTableBody tr');
                rows.each(function() {
                    if ($(this).find('td:eq(0)').text() === companyName) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            }

        });
    </script>
</body>
</html>
