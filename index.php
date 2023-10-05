<?php
// Function to generate XML with properly formatted CDATA section
function generateXML($selectedName, $date, $pdfFilename, $zone, $edition, $pdfText, $desiredPdfFilename, $pageNumber) {
    // Convert the provided date to the "YYYY-MM-DD" format
    $formattedDate = date("Y-m-d", strtotime($date));

    // Escape special characters in the PDF text
    $escapedPdfText = htmlspecialchars($pdfText, ENT_XML1 | ENT_COMPAT, 'UTF-8');

    $xml = '<root>';
    $xml .= '<document id="' . htmlspecialchars($desiredPdfFilename) . '" name="' . htmlspecialchars($desiredPdfFilename) . '" >';
    $xml .= '<issue date="' . htmlspecialchars($formattedDate) . '" publication="' . htmlspecialchars($selectedName) . '" number="' . htmlspecialchars($edition) . '" />';
    $xml .= '<data mime="application/pdf">' . htmlspecialchars($desiredPdfFilename . '.pdf') . '</data>';
    $xml .= '<meta>';
    $xml .= '<value name="title"></value>';
    $xml .= '<value name="date"></value>';
    $xml .= '<value name="authors"></value>';
    // Insert the PDF text as CDATA with properly formatted content
    $xml .= '<value name="summary"><![CDATA[' . $escapedPdfText . ']]></value>';
    $xml .= '</meta>';
    $xml .= '</document>';
    $xml .= '</root>';

    return $xml;
}

// Function to extract text from a PDF file
function extractTextFromPDF($pdfFilename) {
    // Use the pdftotext function to extract text from the PDF file
    $text = shell_exec("pdftotext \"$pdfFilename\" -");
    
    return trim($text); // Remove leading and trailing whitespace
}

// Function to parse the titles.xml file and extract publication or title names
function getNames($nodeName) {
    $names = [];

    $dom = new DOMDocument();
    if ($dom->load("titles.xml")) {
        $nodes = $dom->getElementsByTagName($nodeName);
        foreach ($nodes as $node) {
            $name = $node->getAttribute("name");
            if (!empty($name)) {
                $names[] = $name;
            }
        }
    }

    return $names;
}

// Function to get the zones from zones.xml based on titleRef id
function getZonesForTitleRef($titleRefId) {
    $zones = [];

    $dom = new DOMDocument();
    if ($dom->load("zones.xml")) {
        $zoneNodes = $dom->getElementsByTagName("zone");
        foreach ($zoneNodes as $zoneNode) {
            $titleRef = $zoneNode->getElementsByTagName("titleRef")->item(0);
            if ($titleRef && $titleRef->getAttribute("idref") == $titleRefId) {
                $zoneName = $zoneNode->getAttribute("name");
                if (!empty($zoneName)) {
                    $zones[] = $zoneName;
                }
            }
        }
    }

    return $zones;
}

// Function to get the edition numbers from editionnumbers.xml based on titleRef id
function getEditionNumbersForTitleRef($titleRefId) {
    $editionNumbers = [];

    $dom = new DOMDocument();
    if ($dom->load("editionnumbers.xml")) {
        $editionNodes = $dom->getElementsByTagName("editionNumber");
        foreach ($editionNodes as $editionNode) {
            $titleRef = $editionNode->getElementsByTagName("titleRef")->item(0);
            if ($titleRef && $titleRef->getAttribute("idref") == $titleRefId) {
                $editionName = $editionNode->getAttribute("name");
                if (!empty($editionName)) {
                    $editionNumbers[] = $editionName;
                }
            }
        }
    }

    return $editionNumbers;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form submission
    $selectedName = $_POST["name"];
    $date = $_POST["date"];
    $desiredPdfFilename = $_POST["pdf_filename"]; // Get the desired PDF filename
    $pageNumber = $_POST["page_number"]; // Get the page number

    // Create a ZIP archive in the ./output folder with a name based on PDF naming convention
    $zip_filename = "./output/" . pathinfo($pdf_files["name"][0], PATHINFO_FILENAME) . ".zip";
    $zip = new ZipArchive();
    if ($zip->open($zip_filename, ZipArchive::CREATE) === TRUE) {
        for ($i = 0; $i < count($pdf_files["name"]); $i++) {
            // Handle each uploaded PDF file
            $pdf_filename = $pdf_files["name"][$i];
            $pdf_tmp_file = $pdf_files["tmp_name"][$i];

            // Move the uploaded PDF to the uploads folder
            $upload_dir = "uploads/";
            move_uploaded_file($pdf_tmp_file, $upload_dir . $pdf_filename);

            // Extract text from the PDF
            $pdf_text = extractTextFromPDF($upload_dir . $pdf_filename);

            // Generate XML content with the specified PDF filename and page number
            $xml_content = generateXML($selectedName, $date, $pdf_filename, $zone, $edition, $pdf_text, $desiredPdfFilename, $pageNumber);

            // Generate XML file name based on PDF file name
            $xml_filename = pathinfo($pdf_filename, PATHINFO_FILENAME) . ".xml";
            file_put_contents($xml_filename, $xml_content);

            // Add XML and PDF to the ZIP archive
            $zip->addFile($xml_filename, $xml_filename);
            $zip->addFile($upload_dir . $pdf_filename, $pdf_filename);
        }

        // Close the ZIP archive
        $zip->close();

        // Copy the ZIP file to the Windows share location
        $zipFilename = pathinfo($pdf_files["name"][0], PATHINFO_FILENAME) . '.zip';
        $windowsShareLocation = '\\\\pklgn4bck01\\back4$\\Archive\\PDF\\In\\' . $zipFilename;

        if (copy($zip_filename, $windowsShareLocation)) {
            echo 'Files have been processed and saved in the <a href="' . $zip_filename . '" download>output folder</a>.';
            echo '<br>The PDFs have been sent to the GN4 Archive.';
        } else {
            echo 'Failed to copy the ZIP file to the Windows share location.';
        }

        // Remove the uploaded PDFs and XML files if they exist
        foreach ($pdf_files["name"] as $pdf_filename) {
            $pdf_path = $upload_dir . $pdf_filename;
            $xml_path = pathinfo($pdf_filename, PATHINFO_FILENAME) . ".xml";

            // Check if the files exist before unlinking
            if (file_exists($pdf_path)) {
                unlink($pdf_path);
            }

            if (file_exists($xml_path)) {
                unlink($xml_path);
            }
        }

        // Display an interactive dialogue box with download link after the document is fully loaded
        echo '<script>';
        echo 'document.addEventListener("DOMContentLoaded", function() {';
        echo '  var downloadLink = document.createElement("a");';
        echo '  downloadLink.href = "' . $zip_filename . '";';
        echo '  downloadLink.download = "' . pathinfo($pdf_files["name"][0], PATHINFO_FILENAME) . '.zip";';
        echo '  downloadLink.style.display = "none";';
        echo '  document.body.appendChild(downloadLink);';
        echo '  downloadLink.click();';
        echo '  document.body.removeChild(downloadLink);';

        // Add a notification pop-up
        echo '  var confirmation = confirm("Files have been processed and saved in the output folder. Do you want to download the ZIP file?");';
        echo '  if (confirmation) {';
        echo '    window.location.href = "' . $zip_filename . '";';
        echo '  }';
        echo '});';
        echo '</script>';
    }
}

// Get the publication names from titles.xml
$publication_names = getNames("publication");

// Get the title names from titles.xml
$title_names = getNames("title");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Include a CSS framework like Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Include Pikaday CSS for the date picker -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pikaday/1.8.0/css/pikaday.min.css">

    <!-- Custom CSS for high-resolution design and initial zoom level -->
    <style>
        @font-face {
            font-family: 'San Francisco';
            src: url('./font/SFProDisplay-Light.woff2') format('woff2'),
                font-weight: normal;
            font-style: normal;
        }

        /* Set initial zoom level to 75% */
        body {
            font-family: 'San Francisco', sans-serif;
            background-color: #F0F0F0; /* Light gray background */
            transform: scale(0.80);
            transform-origin: 0 0;
            width: 133.33%; /* (1 / 0.75) = 133.33% to counteract the scale */
        }

        .container {
            margin-top: 30px;
        }

        .btn-hi-res {
            background-color: #007aff; /* Blue button color */
            color: #fff; /* White text */
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-hi-res:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }

        .card {
            background-color: #fff; /* White card background */
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* High-resolution-like window styling */
        .hi-res-window {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
            background-color: #fff;
        }

        /* Highlight style for key elements */
        .highlight {
            color: #007aff; /* Blue highlight color */
            font-weight: bold;
        }

        /* Form styling */
        form {
            margin-top: 20px;
        }

        .form-group label {
            font-weight: bold;
        }

        .form-control {
            border-radius: 5px;
            padding: 10px;
        }

        .form-control-file {
            padding: 10px;
        }

        /* Style for the date input field */
        .date-input {
            position: relative;
        }

        .date-input input {
            padding-right: 30px;
        }

        .date-input .calendar-icon {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- High-resolution-like card -->
        <div class="card">
            <h1 class="text-center">PDF 2 GN4</h1>
            <div class="intro">
                <p class="text-center">Welcome to the GN4 PDF Archiver!</p>
                <p class="text-center">Follow the simple steps below to archive your PDFs into GN4.</p>
                <p class="text-center">Ensure your PDF filenames adhere to the following format:</p>
                <p class="highlight" align="center">XX_DDMMYY_000</p>
                <p align="center"><span class="highlight">XX</span> represents the Title Shortname, <span class="highlight">DDMMYY</span> is the date, and <span class="highlight">000</span> indicates the Page Number.</p>
            </div>

            <!-- Rest of the form and content -->
            <form method="POST" enctype="multipart/form-data">
                <h4>Select Title or Publication Name</h4>
                <div class="form-group">
                    <label for="name">Name:</label>
                    <select class="form-control" name="name" id="name" required>
                        <option value="" disabled selected>Select Name</option>
                        <?php
                        foreach ($publication_names as $name) {
                            echo '<option value="' . $name . '">' . $name . '</option>';
                        }
                        foreach ($title_names as $name) {
                            echo '<option value="' . $name . '">' . $name . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <!-- Hidden Name Type select field -->
                <div class="form-group" style="display: none;">
                    <label for="name_type">Name Type:</label>
                    <select class="form-control" name="name_type" id="name_type" required>
                        <option value="Publication">Publication</option>
                        <option value="Title">Title</option>
                    </select>
                </div>

                <!-- Specify PDF Filename and Page Number -->
                <h4>Specify PDF Filename and Page Number</h4>
                <div class="form-group">
                    <label for="pdf_filename">PDF Filename:</label>
                    <input class="form-control" type="text" name="pdf_filename" id="pdf_filename" required>
                </div>
                <div class="form-group">
                    <label for="page_number">Page Number (Last 3 Digits of Existing Filename):</label>
                    <input class="form-control" type="text" name="page_number" id="page_number" required>
                </div>

                <button class="btn btn-hi-res" type="button" id="goButton">Fetch Zones and Editions</button><br><br>

                <h4>Choose the appropriate Zone & Edition</h4>
                <!-- Zone dropdown -->
                <div class="form-group">
                    <label for="zone">Zone:</label>
                    <select class="form-control" name="zone" id="zone" required>
                        <!-- Options will be populated dynamically based on user selection -->
                    </select>
                </div>

                <!-- Edition Number dropdown -->
                <div class="form-group">
                    <label for="edition">Edition Number:</label>
                    <select class="form-control" name="edition" id="edition" required>
                        <!-- Options will be populated dynamically based on user selection -->
                    </select>
                </div>

                <h4>Use the calendar to select the publication date of the title / publication</h4>
                <div class="form-group date-input">
                    <label for="date">Date (YYYY-MM-DD):</label>
                    <input class="form-control" type="text" name="date" id="date" required readonly>
                    <!-- Calendar icon for date picker -->
                    <span class="calendar-icon" id="calendar-icon">&#128197;</span>
                </div>

                <h4>Select Title / Publication PDFs</h4>
                <div class="form-group">
                    <label for="pdf_files">PDF Files:</label>
                    <input class="form-control-file" type="file" name="pdf_files[]" accept=".pdf" multiple required>
                </div>

                <button class="btn btn-hi-res" type="submit">Upload</button>
            </form>
            <h4>Please wait for the dialogue</h4>
        </div>
    </div>

    <!-- Include Pikaday JavaScript for the date picker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pikaday/1.8.0/pikaday.min.js"></script>
    <script>
        // Initialize Pikaday date picker
        var dateInput = document.getElementById("date");
        var calendarIcon = document.getElementById("calendar-icon");

        var picker = new Pikaday({
            field: dateInput,
            format: "YYYY-MM-DD",
            yearRange: [1900, new Date().getFullYear()], // Customize the range as needed
        });

        // Show the calendar when the calendar icon is clicked
        calendarIcon.addEventListener("click", function () {
            picker.show();
        });

        // Function to format the date when a date is selected
        document.getElementById("date").addEventListener("change", function () {
            var selectedDate = this.value; // Get the selected date
            var formattedDate = new Date(selectedDate).toISOString().split('T')[0]; // Format it as "YYYY-MM-DD"
            this.value = formattedDate; // Set the formatted date back to the input field
        });

        // Function to populate the dropdowns
        function populateDropdowns(selectedName, nameType) {
            var zoneSelect = document.getElementById("zone");
            var editionSelect = document.getElementById("edition");

            // Clear existing options
            zoneSelect.innerHTML = '';
            editionSelect.innerHTML = '';

            // Ensure selectedName and nameType are not empty
            if (selectedName && nameType) {
                // AJAX request to fetch zone data based on selectedName and nameType
                var xhr = new XMLHttpRequest();
                xhr.open("GET", "fetch_zones.php?name=" + selectedName + "&name_type=" + nameType, true);
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        var data = JSON.parse(xhr.responseText);

                        // Populate the Zone dropdown
                        for (var i = 0; i < data.ZoneNames.length; i++) {
                            var option = document.createElement("option");
                            option.text = data.ZoneNames[i];
                            zoneSelect.add(option);
                        }
                    }
                };
                xhr.send();

                // AJAX request to fetch edition data based on selectedName and nameType
                var xhrEdition = new XMLHttpRequest();
                xhrEdition.open("GET", "fetch_editionnumbers.php?name=" + selectedName + "&name_type=" + nameType, true);
                xhrEdition.onreadystatechange = function () {
                    if (xhrEdition.readyState === 4 && xhrEdition.status === 200) {
                        var data = JSON.parse(xhrEdition.responseText);

                        // Populate the Edition Number dropdown
                        for (var i = 0; i < data.EditionNumbers.length; i++) {
                            var option = document.createElement("option");
                            option.text = data.EditionNumbers[i];
                            editionSelect.add(option);
                        }
                    }
                };
                xhrEdition.send();
            }
        }

        // Event listener for the "Fetch Zones and Editions" button click
        document.getElementById("goButton").addEventListener("click", function () {
            var selectedName = document.getElementById("name").value;
            var nameType = document.getElementById("name_type").value;
            populateDropdowns(selectedName, nameType);
        });
    </script>
</body>
</html>
