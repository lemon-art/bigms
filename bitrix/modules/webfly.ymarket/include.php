<?
function wfYmarketAgent() {
    $agentFolder = COption::GetOptionString("webfly.ymarket", "agentFolder", "/y-market/", false,false);
    BXClearCache(true, "/y-market/");
    BXClearCache(true, $agentFolder);
    $ch = curl_init();

// set URL and other appropriate options
    
    curl_setopt($ch, CURLOPT_URL, $_SERVER['SERVER_NAME'] . $agentFolder);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 40);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 0);


// grab URL and pass it to the browser
    curl_exec($ch);

// close cURL resource, and free up system resources
    curl_close($ch);
    return "wfYmarketAgent();";
}
?>