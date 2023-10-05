<?php
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Get the selected name and name type from the GET request
    $selectedName = $_GET["name"];
    $nameType = $_GET["name_type"];
    
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
    
    // Use the selected name and name type to find the corresponding titleRef id
    $titleRefId = "";
    
    // Load titles.xml to find the titleRef id based on selectedName
    $domTitles = new DOMDocument();
    if ($domTitles->load("titles.xml")) {
        $titleNodes = $domTitles->getElementsByTagName("title");
        $publicationNodes = $domTitles->getElementsByTagName("publication");

        foreach ($titleNodes as $titleNode) {
            if ($titleNode->getAttribute("name") == $selectedName) {
                $titleRefId = $titleNode->getAttribute("id");
                break;
            }
        }

        // If not found in titles, check in publications
        if (empty($titleRefId)) {
            foreach ($publicationNodes as $publicationNode) {
                if ($publicationNode->getAttribute("name") == $selectedName) {
                    $titleRefId = $publicationNode->getAttribute("id");
                    break;
                }
            }
        }
    }
    
    // Get the edition numbers for the selected titleRef id
    $editionNumbers = getEditionNumbersForTitleRef($titleRefId);
    
    // Send the JSON response
    $response = ["EditionNumbers" => $editionNumbers];
    header("Content-Type: application/json");
    echo json_encode($response);
}
?>
