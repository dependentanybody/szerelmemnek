<?php
// Set message user sees
$message = "<h1>404 Not Found</h1>";

// Set variables
$ip = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? '[cloudflare reports] ' . $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR'];
$port = $_SERVER['REMOTE_PORT'];
$agent = $_SERVER['HTTP_USER_AGENT'];
$href = $_SERVER['HTTP_REFERER'];
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
$rdate = date('Y-m-d H:i:s');

// Set storage type
$type = "file"; // "file" or "mysql"

// MySQL storage config
$db_host = 'localhost';
$db_username = 'records';
$db_password = 'records';
$db_database = 'records';

// File storage config
$file = "log.txt";

// Handle storage
if (!empty($type) && $type == "file") {
    if (is_writable($file)) {
        $fh = fopen($file, 'a');
        fwrite($fh, "| Connection from $ip at $rdate\n");
        fwrite($fh, "| Hostname: $hostname\n");
        fwrite($fh, "| Port: $port\n");
        fwrite($fh, "| User Agent: $agent\n");
        fwrite($fh, "| HTTP Referer: $href\n");
        fclose($fh);
        echo $message;
    } else {
        chmod($file, 0777);
        die("<pre>Content could not load. Please try again in a few seconds.\n</pre>");
    }
} elseif (!empty($type) && $type == "mysql") {
    $sqlconn = mysqli_connect($db_host, $db_username, $db_password, $db_database);
    if (mysqli_connect_errno()) {
        printf("Could not connect to MySQL database: %s\n", mysqli_connect_error());
        exit();
    }
    mysqli_query($sqlconn, "CREATE TABLE IF NOT EXISTS records.logs (
        IP TEXT(16) NOT NULL,
        DATE TEXT(30) NOT NULL,
        HOSTNAME TEXT(255) NOT NULL,
        PORT INT(6) NOT NULL,
        USERAGENT TEXT(255) NOT NULL,
        HTTPREFERER TEXT(255) NOT NULL
    )");
    mysqli_query($sqlconn, "INSERT INTO records.logs VALUES (
        '$ip',
        '$rdate',
        '$hostname',
        '$port',
        '$agent',
        '$href'
    )");
    mysqli_close($sqlconn);
    echo $message;
}
?>
