   <script>
    // List of companies (example: S&P 500 companies)
    const companies = [
  "Agilent Technologies",
  "American Airlines Group",
  "Apple",
  "AbbVie",
  "Airbnb",
  "Abbott",
  "Arch Capital Group",
  "Accenture",
  "Adobe Inc.",
  "Analog Devices",
  "Archer-Daniels-Midland",
  "Automatic Data Processing",
  "Autodesk",
  "Ameren",
  "American Electric Power",
  "AES Corporation",
  "Aflac",
  "American International Group",
  "Assurant",
  "Arthur J. Gallagher & Co.",
  "Akamai",
  "Albemarle Corporation",
  "Align Technology",
  "Allstate",
  "Allegion",
  "Applied Materials",
  "Amcor",
  "Advanced Micro Devices",
  "Ametek",
  "Amgen",
  "Ameriprise Financial",
  "American Tower",
  "Amazon.com Inc.",
  "Arista Networks",
  "Ansys",
  "Aon",
  "A. O. Smith",
  "APA Corporation",
  "Air Products and Chemicals",
  "Amphenol",
  "Aptiv",
  "Alexandria Real Estate Equities",
  "Atmos Energy",
  "AvalonBay Communities",
  "Broadcom Inc.",
  "Avery Dennison",
  "American Water Works",
  "Axon Enterprise",
  "American Express",
  "AutoZone",
  "Boeing",
  "Bank of America",
  "Ball Corporation",
  "Baxter International",
  "Bath & Body Works, Inc.",
  "Best Buy",
  "Becton Dickinson",
  "Franklin Templeton",
  "Brown Forman",
  "Bunge Global SA",
  "Biogen",
  "Bio-Rad",
  "Bank of New York Mellon",
  "Booking Holdings",
  "Baker Hughes",
  "Builders FirstSource",
  "BlackRock",
  "Bristol Myers Squibb",
  "Broadridge Financial Solutions",
  "Berkshire Hathaway",
  "Brown & Brown",
  "Boston Scientific",
  "BorgWarner",
  "Blackstone",
  "Boston Properties",
  "Citigroup",
  "Conagra Brands",
  "Cardinal Health",
  "Carrier Global",
  "Caterpillar Inc.",
  "Chubb Limited",
  "Cboe Global Markets",
  "CBRE Group",
  "Crown Castle",
  "Carnival",
  "Cadence Design Systems",
  "CDW",
  "Celanese",
  "Constellation Energy",
  "CF Industries",
  "Citizens Financial Group",
  "Church & Dwight",
  "CH Robinson",
  "Charter Communications",
  "Cigna",
  "Cincinnati Financial",
  "Colgate-Palmolive",
  "Clorox",
  "Comerica",
  "Comcast",
  "CME Group",
  "Chipotle Mexican Grill",
  "Cummins",
  "CMS Energy",
  "Centene Corporation",
  "CenterPoint Energy",
  "Capital One",
  "CooperCompanies",
  "ConocoPhillips",
  "Cencora",
  "Costco",
  "Corpay",
  "Campbell Soup Company",
  "Copart",
  "Camden Property Trust",
  "Charles River Laboratories",
  "Salesforce",
  "Cisco",
  "CoStar Group",
  "CSX",
  "Cintas",
  "Catalent",
  "Coterra",
  "Cognizant",
  "Corteva",
  "CVS Health",
  "Chevron Corporation",
  "Caesars Entertainment",
  "Dominion Energy",
  "Delta Air Lines",
  "Dayforce",
  "DuPont",
  "John Deere",
  "Deckers Brands",
  "Discover Financial",
  "Dollar General",
  "Quest Diagnostics",
  "DR Horton",
  "Danaher Corporation",
  "Walt Disney Company",
  "Digital Realty",
  "Dollar Tree",
  "Healthpeak",
  "Dover Corporation",
  "Dow Inc.",
  "Domino's",
  "Darden Restaurants",
  "DTE Energy",
  "Duke Energy",
  "DaVita Inc.",
  "Devon Energy",
  "Dexcom",
  "Electronic Arts",
  "eBay",
  "Ecolab",
  "Consolidated Edison",
  "Equifax",
  "Everest Re",
  "Edison International",
  "EstÃ©e Lauder Companies",
  "Elevance Health",
  "Eastman Chemical Company",
  "Emerson Electric",
  "Enphase",
  "EOG Resources",
  "EPAM Systems",
  "Equinix",
  "Equity Residential",
  "EQT Corporation",
  "Eversource",
  "Essex Property Trust",
  "Eaton Corporation",
  "Entergy",
  "Etsy",
  "Evergy",
  "Edwards Lifesciences",
  "Exelon",
  "Expeditors International",
  "Expedia Group",
  "Extra Space Storage",
  "Ford Motor Company",
  "Diamondback Energy",
  "Fastenal",
  "Freeport-McMoRan",
  "FactSet",
  "FedEx",
  "FirstEnergy",
  "F5, Inc.",
  "Fiserv",
  "Fair Isaac",
  "Fidelity National Information Services",
  "Fifth Third Bank",
  "FMC Corporation",
  "Fox Corporation (Class B)",
  "Fox Corporation (Class A)",
  "Federal Realty",
  "First Solar",
  "Fortinet",
  "Fortive",
  "General Dynamics",
  "GE Aerospace",
  "GE HealthCare",
  "Gen Digital",
  "GE Vernova",
  "Gilead Sciences",
  "General Mills",
  "Globe Life",
  "Corning Inc.",
  "General Motors",
  "Generac",
  "Alphabet Inc. (Class C)",
  "Alphabet Inc.",
  "Genuine Parts Company",
  "Global Payments",
  "Garmin",
  "Goldman Sachs",
  "W. W. Grainger",
  "Halliburton",
  "Hasbro",
  "Huntington Bancshares",
  "HCA Healthcare",
  "Home Depot (The)",
  "Hess Corporation",
  "Hartford (The)",
  "Huntington Ingalls Industries",
  "Hilton Worldwide",
  "Hologic",
  "Honeywell",
  "Hewlett Packard Enterprise",
  "HP Inc.",
  "Hormel Foods",
  "Henry Schein",
  "Host Hotels & Resorts",
  "Hershey's",
  "Hubbell Incorporated",
  "Humana",
  "Howmet Aerospace",
  "IBM",
  "Intercontinental Exchange",
  "Idexx Laboratories",
  "IDEX Corporation",
  "International Flavors & Fragrances",
  "Illumina",
  "Incyte",
  "Intel",
  "Intuit",
  "Invitation Homes",
  "International Paper",
  "Interpublic Group of Companies (The)",
  "IQVIA",
  "Ingersoll Rand",
  "Iron Mountain",
  "Intuitive Surgical",
  "Gartner",
  "Illinois Tool Works",
  "Invesco",
  "Jacobs Solutions",
  "J.B. Hunt",
  "Jabil",
  "Johnson Controls",
  "Jack Henry & Associates",
  "Johnson & Johnson",
  "Juniper Networks",
  "JPMorgan Chase",
  "Kellanova",
  "Keurig Dr Pepper",
  "KeyCorp",
  "Keysight",
  "Kraft Heinz",
  "Kimco Realty",
  "KLA Corporation",
  "Kimberly-Clark",
  "Kinder Morgan",
  "CarMax",
  "Coca-Cola Company",
  "Kroger",
  "Kenvue",
  "Loews Corporation",
  "Leidos",
  "Lennar",
  "LabCorp",
  "L3Harris",
  "Linde plc",
  "LKQ Corporation",
  "Eli Lilly and Company",
  "Lockheed Martin",
  "Alliant Energy",
  "Lowe's",
  "Lam Research",
  "Lululemon Athletica",
  "Southwest Airlines",
  "Las Vegas Sands",
  "Lamb Weston",
  "LyondellBasell",
  "Live Nation Entertainment",
  "Mastercard",
  "Mid-America Apartment Communities",
  "Marriott International",
  "Masco",
  "McDonald's",
  "Microchip Technology",
  "McKesson Corporation",
  "Moody's Corporation",
  "Mondelez International",
  "Medtronic",
  "MetLife",
  "Meta Platforms",
  "MGM Resorts",
  "Mohawk Industries",
  "McCormick & Company",
  "MarketAxess",
  "Martin Marietta Materials",
  "Marsh McLennan",
  "3M",
  "Monster Beverage",
  "Altria",
  "Molina Healthcare",
  "Mosaic Company (The)",
  "Marathon Petroleum",
  "Monolithic Power Systems",
  "Merck & Co.",
  "Moderna",
  "Marathon Oil",
  "Morgan Stanley",
  "MSCI",
  "Microsoft Corporation",
  "Motorola Solutions",
  "M&T Bank",
  "Match Group",
  "Mettler Toledo",
  "Micron Technology",
  "Norwegian Cruise Line Holdings",
  "Nasdaq, Inc.",
  "Nordson Corporation",
  "NextEra Energy",
  "Newmont",
  "Netflix",
  "NiSource",
  "Nike, Inc.",
  "Northrop Grumman",
  "ServiceNow",
  "NRG Energy",
  "Norfolk Southern Railway",
  "NetApp",
  "Northern Trust",
  "Nucor",
  "NVIDIA Corporation",
  "NVR, Inc.",
  "News Corp (Class B)",
  "News Corp (Class A)",
  "NXP Semiconductors",
  "Realty Income",
  "Old Dominion",
  "ONEOK",
  "Omnicom Group",
  "ON Semiconductor",
  "Oracle Corporation",
  "O'Reilly Auto Parts",
  "Otis Worldwide",
  "Occidental Petroleum",
  "Palo Alto Networks",
  "Paramount Global",
  "Paycom",
  "Paychex",
  "Paccar",
  "PG&E Corporation",
  "Public Service Enterprise Group",
  "PepsiCo",
  "Pfizer",
  "Principal Financial Group",
  "Procter & Gamble",
  "Progressive Corporation",
  "Parker Hannifin",
  "PulteGroup",
  "Packaging Corporation of America",
  "Prologis",
  "Philip Morris International",
  "PNC Financial Services",
  "Pentair",
  "Pinnacle West",
  "Insulet Corporation",
  "Pool Corporation",
  "PPG Industries",
  "PPL Corporation",
  "Prudential Financial",
  "Public Storage",
  "Phillips 66",
  "PTC",
  "Quanta Services",
  "PayPal",
  "Qualcomm",
  "Qorvo",
  "Royal Caribbean Group",
  "Regency Centers",
  "Regeneron",
  "Regions Financial Corporation",
  "Robert Half",
  "Raymond James",
  "Ralph Lauren Corporation",
  "ResMed",
  "Rockwell Automation",
  "Rollins, Inc.",
  "Roper Technologies",
  "Ross Stores",
  "Republic Services",
  "RTX Corporation",
  "Revvity",
  "SBA Communications",
  "Starbucks",
  "Charles Schwab Corporation",
  "Sherwin-Williams",
  "J.M. Smucker Company (The)",
  "Schlumberger",
  "Supermicro",
  "Snap-on",
  "Synopsys",
  "Southern Company",
  "Solventum",
  "Simon Property Group",
  "S&P Global",
  "Sempra Energy",
  "Steris",
  "Steel Dynamics",
  "State Street Corporation",
  "Seagate Technology",
  "Constellation Brands",
  "Stanley Black & Decker",
  "Skyworks Solutions",
  "Synchrony Financial",
  "Stryker Corporation",
  "Sysco",
  "AT&T",
  "Molson Coors Beverage Company",
  "TransDigm Group",
  "Teledyne Technologies",
  "Bio-Techne",
  "TE Connectivity",
  "Teradyne",
  "Truist",
  "Teleflex",
  "Target Corporation",
  "TJX Companies",
  "Thermo Fisher Scientific",
  "T-Mobile US",
  "Tapestry, Inc.",
  "Targa Resources",
  "Trimble Inc.",
  "T. Rowe Price",
  "Travelers Companies (The)",
  "Tractor Supply",
  "Tesla, Inc.",
  "Tyson Foods",
  "Trane Technologies",
  "Take-Two Interactive",
  "Texas Instruments",
  "Textron",
  "Tyler Technologies",
  "United Airlines Holdings",
  "Uber",
  "UDR, Inc.",
  "Universal Health Services",
  "Ulta Beauty",
  "UnitedHealth Group",
  "Union Pacific Corporation",
  "United Parcel Service",
  "United Rentals",
  "U.S. Bank",
  "Visa Inc.",
  "Vici Properties",
  "Valero Energy",
  "Veralto",
  "Vulcan Materials Company",
  "Verisk",
  "Verisign",
  "Vertex Pharmaceuticals",
  "Vistra",
  "Ventas",
  "Viatris",
  "Verizon",
  "Wabtec",
  "Waters Corporation",
  "Walgreens Boots Alliance",
  "Warner Bros. Discovery",
  "Western Digital",
  "WEC Energy Group",
  "Welltower",
  "Wells Fargo",
  "Waste Management",
  "Williams Companies",
  "Walmart",
  "W. R. Berkley Corporation",
  "WestRock",
  "West Pharmaceutical Services",
  "Willis Towers Watson",
  "Weyerhaeuser",
  "Wynn Resorts",
  "Xcel Energy",
  "ExxonMobil",
  "Xylem Inc.",
  "Yum! Brands",
  "Zimmer Biomet",
  "Zebra Technologies",
  "Zoetis"
];
 function showSuggestions() {
            const input = document.getElementById('company-input').value.toUpperCase();
            const suggestionBox = document.getElementById('suggestions');
            suggestionBox.innerHTML = '';

            if (input.length > 0) {
                const filteredCompanies = companies.filter(company => company.toUpperCase().startsWith(input));

                filteredCompanies.forEach(company => {
                    const div = document.createElement('div');
                    div.textContent = company;
                    div.onclick = () => {
                        document.getElementById('company-input').value = company;
                        suggestionBox.innerHTML = '';
                    };
                    suggestionBox.appendChild(div);
                });
            }
        }
</script>
