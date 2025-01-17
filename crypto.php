<?php
include "db.php";
if(isset($_POST['setnotification'])){
    $useremail=$_POST['useremail'];
    $msg='';
    if (isset($_POST["symbolName"])) {
        $symbolName=$_POST["symbolName"];
        $downValue=$_POST["downValue"];
        $query = "INSERT INTO `down_value`(`email`, `symbol`, `down_more`,`status`) VALUES ('$useremail','$symbolName','$downValue','not sent')";
        if ($con->query($query) === TRUE) {
            $msg.='<div class="alert alert-success alert-dismissible">
                   <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                  <strong>Success!</strong> Record Has Been Saved Successfully.</div>';
        } else {
             $msg ="Error: " . $query . "<br>" . $con->error;
        }  
    }
    
    if (isset($_POST["symbolnameAllTime"])) {
        $msg='';
        $symbolnameAllTime=$_POST["symbolnameAllTime"];
        $query = "INSERT INTO `new_ath`(`email`, `symbol`,`status`) VALUES ('$useremail','$symbolnameAllTime','not sent')";
        if ($con->query($query) === TRUE) {
            $msg.='<div class="alert alert-success alert-dismissible">
                   <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                  <strong>Success!</strong> Record Has Been Saved Successfully.</div>';
        } else {
             $msg ="Error: " . $query . "<br>" . $con->error;
        }      
    }
}

 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Correction Territory</title>

    <link rel="icon" href="images/icon.png" type="image/x-icon">
        <!-- All in One SEO 4.6.7.1 - aioseo.com -->
        <meta name="robots" content="max-image-preview:large" />
        <meta name="generator" content="All in One SEO (AIOSEO) 4.6.7.1" />
        <meta property="og:locale" content="en_US" />
        <meta property="og:site_name" content="Correction Territory - Invest Simply" />
        <meta property="og:type" content="article" />
        <meta property="og:title" content="Tracking Stocks' Gap from All-Time Highs" />
        <meta property="og:url" content="https://correctionterritory.com/blogs/home/" />
        <meta property="article:published_time" content="2024-05-20T20:33:00+00:00" />
        <meta property="article:modified_time" content="2024-07-14T21:13:56+00:00" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="Tracking Stocks' Gap from All-Time Highs" />

        <meta name="viewport" content="width=device-width, initial-scale=1" />
  
        <link rel="icon" href="images/icon.jpg" type="image/x-icon">
        <link rel="icon" href="images/icon.jpg" type="image/jpg">
        <link rel="apple-touch-icon" href="images/icon.jpg">


        <link rel="canonical" href="https://correctionterritory.com/" class="yoast-seo-meta-tag" />
        <meta property="og:locale" content="en_US" class="yoast-seo-meta-tag" />
        <meta property="og:type" content="article" class="yoast-seo-meta-tag" />
        <meta property="og:title" content="Tracking Stocks' Gap from All-Time Highs" class="yoast-seo-meta-tag" />
        <meta property="og:url" content="https://correctionterritory.com" class="yoast-seo-meta-tag" />
        <meta property="og:site_name" content="Correction Territory" class="yoast-seo-meta-tag" />
        <meta property="article:modified_time" content="2024-07-14T21:13:56+00:00" class="yoast-seo-meta-tag" />
        <meta name="twitter:card" content="summary_large_image" class="yoast-seo-meta-tag" />
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-8754771874266985"
     crossorigin="anonymous"></script>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
     <script src="https://www.google.com/recaptcha/api.js?render=6Lf9TEcqAAAAAHau8MDGhNq4BmRG2sjiaXhaX3P9"></script>
     <style type="text/css">
         <style>
         #loadingicon {
            /* Initial styles for loading icon */
        }
        .hidden {
            display: none;
                    }
                    .fa {
                font-size: 12px;
            }
              #company-input::-webkit-input-placeholder {
                color: #7a7a7a;
              }

              #company-input::-moz-placeholder {
                color: #7a7a7a;
              }

              #company-input:-ms-input-placeholder {
                color: #7a7a7a;
              }

              #company-input::placeholder {
                color: #7a7a7a;
              }
              
         </style>

     </style>
</head>

<body>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
<link href="custom.css" rel="stylesheet">
<?php include "header.php" ?>

 <div id="tablesList">
   
<div class="loadingicon" id="loadingicon">
    <img src="images/loading-animation.gif" style="max-width:50px;display:block;margin: auto;padding:50px 0px;">
</div>
<script type="text/javascript">
    function reDrawTable(){
        location.reload(true);
    }
</script>
   <table id="stockTable" class="hidden table table-bordered table-striped" style="border-radius: 10px;">
    <thead>
          <tr>
            <th class="cname" data-column="name" width="25%"  style="vertical-align: middle;">Crypto</th>
            <th data-column="lastClosePrice"  style="vertical-align: middle;">Current<br/> Price ($)</th>
            <th data-column="allTimeHighPrice"  style="vertical-align: middle;">All Time<br/> High ($)</th>
            <th data-column="correctionRange"  style="vertical-align: middle;">Correction<br/> Range (%)</th>
        </tr>
    </thead>
    <tbody id="favoritesTableBody">
        <?php

         $categories_query = mysqli_query($con, "SELECT * FROM `crypto`");
    while ($fetch_categories = mysqli_fetch_array($categories_query)) {
     
        $lastClosePrice=$fetch_categories["last_close_price"];
        $allTimeHighPrice=$fetch_categories["all_time_high"];
        $correctionRange = ($lastClosePrice - $allTimeHighPrice) / $allTimeHighPrice * 100;
        $correctionRange = number_format($correctionRange, 2);

        ?>
        <tr>
            <td class="cname">
                <a href="https://www.google.com/finance/quote/<?php echo $fetch_categories["symbol"]; ?>-USD?window=1Y" target="_blank">
                    <?php echo $fetch_categories["name"]; ?>
                </a>
            </td>
              <td><?php echo is_numeric($fetch_categories["last_close_price"]) ? number_format($fetch_categories["last_close_price"], 2) : ''; ?></td>
                <td><?php echo is_numeric($fetch_categories["all_time_high"]) ? number_format($fetch_categories["all_time_high"], 2) : ''; ?></td>
            <td class="<?php if ($correctionRange >= -5) { echo 'bg-danger'; } else if ($correctionRange >= -15) { echo 'bg-warning'; } else { echo 'bg-success'; } ?>"><?php echo $correctionRange; ?></td>
        </tr>
<style type="text/css">
    .checked {
    background-color: black; /* Selected background */
    color: white; /* Selected text color */
}
}
.checked:hover {
    background-color: black !important; /* Selected background */
    color: white !important; /* Selected text color */
}
</style>

        <?php } $con->close(); ?>
        
    </tbody>
</table>
<script>
        document.getElementById('datatext').style.display="none";
        setTimeout(function() {
            document.getElementById('loadingicon').classList.add('hidden');
            document.getElementById('stockTable').classList.remove('hidden');
            document.getElementById('datatext').style.display="block";
        }, 1); // 4000 milliseconds = 4 seconds
    </script>

    </div>
<br/>
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-8754771874266985"
     crossorigin="anonymous"></script>
<!-- Unit1 -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-8754771874266985"
     data-ad-slot="7225743774"
     data-ad-format="auto"
     data-full-width-responsive="true"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>
</div>
<!-- Include JavaScript for handling favorite functionality -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const heartIcons = document.querySelectorAll('.heart-icon');

        heartIcons.forEach(icon => {
            icon.addEventListener('click', function() {
                const symbol = this.getAttribute('data-symbol');
                this.classList.toggle('favorited');
                const favorites = JSON.parse(localStorage.getItem('favorites')) || [];
                
                if (this.classList.contains('favorited')) {
                    this.innerHTML = '<i class="fa fa-heart"></i>'; // Filled heart
                    favorites.push(symbol);
                } else {
                    this.innerHTML = '<i class="fa fa-heart"></i>'; // Empty heart
                    const index = favorites.indexOf(symbol);
                    if (index > -1) {
                        favorites.splice(index, 1);
                    }
                }
                
                localStorage.setItem('favorites', JSON.stringify(favorites));
            });
        });

        const savedFavorites = JSON.parse(localStorage.getItem('favorites')) || [];
        heartIcons.forEach(icon => {
            if (savedFavorites.includes(icon.getAttribute('data-symbol'))) {
                icon.classList.add('favorited');
                icon.innerHTML = '<i class="fa fa-heart"></i>'; // Filled heart
            }
        });
    });
</script>

<!-- Include some CSS for the heart icon -->
<style>
    .heart-icon {
        cursor: pointer;
        margin-right: 0px;
        color: #E4E4E4;
        font-size: 23px;
    }

    .heart-icon.favorited {
        font-size: 23px;
        color: #F7D1DF;

    }
</style>
<script>
    $(document).ready(function() {
         var table = $('#stockTable').DataTable({
    searching: false,
    paging: false,
    info: false,
    order: [], // Keep this empty if you don't want any default sorting at initialization
    columnDefs: [
        { orderSequence: ["desc", "asc"], targets: "_all" }, // Default order sequence for all columns
        { orderable: false, targets: 8 } // Disable sorting for the "Correction Range" column (index 7)
    ]
});


    


        $('input[name="filterOption"]').on('change', function() {
            filterTable(this.value);
        });

        $('#companySelect').on('change', function() {
            filterTableByCompany(this.value);
        });

        function filterTable(filterValue) {
            let rows = $('#favoritesTableBody tr');
            rows.each(function() {
                let correctionRange = parseFloat($(this).find('td:eq(5)').text());
                if (filterValue === 'magnificentSeven') {
                    let magnificentSeven = ['Apple Inc.', 'Alphabet Inc.', 'Microsoft Corporation', 'Amazon.com Inc.', 'Meta Platforms', 'Tesla, Inc.', 'NVIDIA Corporation'];
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
            let rows = $('#favoritesTableBody tr');
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


    <script>
    $(document).ready(function() {
        // Trigger the AJAX call when a radio button is selected
        $("input[name='filterOptions']").on("change", function() {
            var selectedValue = $('input[name="filterOptions"]:checked').val(); // Get the selected radio button value
            var customnumber=$('#customnumber').val();
            // Check if the selected value is 'favorite'
            
            if (selectedValue === 'favorite') {
                var favorites = JSON.parse(localStorage.getItem('favorites')) || [];

                    // Create a form
                    var form = document.createElement("form");
                    form.method = "POST";
                    form.action = "favorite.php?filter=favorites";

                    // Create a hidden input field for favorites
                    var input = document.createElement("input");
                    input.type = "hidden";
                    input.name = "favoriteSymbols";
                    input.value = JSON.stringify(favorites); // Pass the favorites as a JSON string

                    // Append the input to the form
                    form.appendChild(input);

                    // Append the form to the body and submit it
                    document.body.appendChild(form);
                    form.submit();

            } else {

                if (selectedValue === 'all'){
                    console.log("test");
                    $.ajax({
                        url: "index.php",
                        type: "POST",
                        data: {
                            filterOption: selectedValue,
                            customnumber:customnumber
                        },
                        cache: false,
                        success: function(result) {
                            $("#tablesList").html(result); // Insert result into the tables list div
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX Error: ", status, error);
                            $("#tablesList").html("<p>An error occurred while fetching data.</p>");
                        }
                         });

                }
                else{
                  // Handle other filter options


                $.ajax({
                    url: "myData.php",
                    type: "POST",
                    data: {
                        filterOption: selectedValue,
                        customnumber:customnumber
                    },
                    cache: false,
                    success: function(result) {
                        $("#tablesList").html(result); // Insert result into the tables list div
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: ", status, error);
                        $("#tablesList").html("<p>An error occurred while fetching data.</p>");
                    }
                });  
                }
                
            }
        });
    });
</script>

<?php include "footer.php"; ?>
<!-- Cookies Consent Popup -->
<div id="cookieConsent" class="cookie-consent">
  <p>This website uses cookies to ensure you get the best experience on our website. 
    <a href="/terms-and-conditions.php">Learn more</a>
  </p>
  <button id="acceptCookies" class="btn btn-primary btn-sm mt-2">Accept</button>
</div>
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function () {
    // Check if cookie consent is already given
    if (!localStorage.getItem("cookiesAccepted")) {
        document.getElementById("cookieConsent").style.display = "block";
    }

    // Set cookie consent on button click
    document.getElementById("acceptCookies").addEventListener("click", function () {
        localStorage.setItem("cookiesAccepted", "true");
        document.getElementById("cookieConsent").style.display = "none";
    });
});

</script>
<script>
    // Call reCAPTCHA v3 on page load
    grecaptcha.ready(function() {
      grecaptcha.execute('6Lf9TEcqAAAAAHau8MDGhNq4BmRG2sjiaXhaX3P9', {action: 'homepage'}).then(function(token) {
        // Send the token to the backend using AJAX or a form submission
        fetch('/verify-recaptcha', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            recaptcha_token: token
          })
        }).then(function(response) {
          return response.json();
        }).then(function(data) {
          // Handle the response from the server
          if (data.success) {
            console.log('Verified!');
          } else {
            console.log('Failed verification.');
          }
        });
      });
    });
  </script>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST data (reCAPTCHA token)
    $recaptcha_token = file_get_contents("php://input");
    $recaptcha_data = json_decode($recaptcha_token, true);
    $recaptcha_token = $recaptcha_data['recaptcha_token'];

    // Verify the token with Google
    $recaptcha_secret = '6Lf9TEcqAAAAAI5oeZjOf08LhtOrAY4ueADnqDfI';
    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';

    $response = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_token);
    $response_data = json_decode($response);

    // Check if verification was successful and the score is high enough
    if ($response_data->success && $response_data->score >= 0.5) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>

<script>
        // Function to set a cookie
        function setCookie(name, value, days) {
            let expires = "";
            if (days) {
                const date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + (value || "") + expires + "; path=/";
        }

        // Function to get a cookie
        function getCookie(name) {
            const nameEQ = name + "=";
            const ca = document.cookie.split(';');
            for (let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) === ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        }

        // Function to display the default column based on the cookie
        function setDefaultColumn() {
            const defaultColumn = getCookie("defaultColumn") || "changeToday"; // Default to Change Today if no cookie is set
            const toggleColumnHeader = document.getElementById('toggleColumnHeader');
            const toggleColumnCells = document.querySelectorAll('.toggle-column');

            const columnData = {
                peRatio: { header: 'P/E Ratio', dataAttribute: 'data-pe' },
                changeToday: { header: 'Change(%)', dataAttribute: 'data-change-today' },
                avgVolume: { header: 'Volume(M)', dataAttribute: 'data-avg-volume' },
                marketCap: { header: 'MKT Cap', dataAttribute: 'data-market-cap' },
                highCount: { header: 'High Count', dataAttribute: 'data-high-count' },
                lastHigh: { header: 'Last High', dataAttribute: 'data-last-high' }
            };

            // Set the header and cells based on the cookie
            const column = columnData[defaultColumn];
            toggleColumnHeader.querySelector('span').textContent = column.header;
            toggleColumnCells.forEach(function(cell) {
                const newData = cell.getAttribute(column.dataAttribute);
                cell.textContent = newData;
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            setDefaultColumn(); // Set the default column on page load

            const columnToggles = document.querySelectorAll('.column-toggle');
            const toggleColumnHeader = document.getElementById('toggleColumnHeader'); // Select the header for the 7th column
            const toggleColumnCells = document.querySelectorAll('.toggle-column'); // Select all cells in the 7th column

            const columnData = {
                peRatio: { header: 'P/E Ratio', dataAttribute: 'data-pe' },
                changeToday: { header: 'Change(%)', dataAttribute: 'data-change-today' },
                avgVolume: { header: 'Volume(M)', dataAttribute: 'data-avg-volume' },
                marketCap: { header: 'MKT Cap', dataAttribute: 'data-market-cap' },
                highCount: { header: 'High Count', dataAttribute: 'data-high-count' },
                lastHigh: { header: 'Last High', dataAttribute: 'data-last-high' }
            };

            columnToggles.forEach(function(toggle) {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();

                    const columnKey = this.getAttribute('data-column'); // Get which column was clicked
                    const column = columnData[columnKey]; // Find corresponding data from the object

                    // Update the header for the 7th column
                    toggleColumnHeader.querySelector('span').textContent = column.header;

                    // Update each cell in the 7th column using the respective data attribute
                    toggleColumnCells.forEach(function(cell) {
                        const newData = cell.getAttribute(column.dataAttribute);
                        cell.textContent = newData;
                    });

                    // Set the cookie for the selected column
                    setCookie("defaultColumn", columnKey, 7); // Cookie expires in 7 days
                });
            });
        });
    </script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php 
include "suggestions.php";
function convertVolume($volume) {
                    // Ensure $volume is numeric
                    if (!is_numeric($volume) || $volume == '') {
                        return [
                            'billions' => 0,
                            'millions' => 0
                        ];
                    }

                    $billions = $volume / 1_000_000_000;
                    $billions_rounded = round($billions, 2);
                    
                    $millions = $volume / 1_000_000;
                    $millions_rounded = round($millions, 2);

                    return [
                        'billions' => $billions_rounded,
                        'millions' => $millions_rounded
                    ];
                }
 ?>

</body>
</html>
