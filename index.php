<?php 

/* IP-address.ws v0.5
    © 2009 Cole Maclean
    TODO: build out template system - remove some duplication
*/

require 'config.php';

if (DEBUG) {
  $start = utime();
}

require 'template.php';
require 'strings.php';

$data = array(
    "ip" => $_SERVER['REMOTE_ADDR'],
    "hostname" => gethostbyaddr($_SERVER['REMOTE_ADDR']),
    "language" => $_SERVER['HTTP_ACCEPT_LANGUAGE'],
    "useragent" => $_SERVER['HTTP_USER_AGENT'],
    "host" => $_SERVER['HTTP_HOST'],
    "analytics_code" => ANALYTICS_CODE
    );

// Route the request - choose page and format based on the URI
if (isset($_SERVER['REQUEST_URI']) && ($_SERVER['REQUEST_URI'] != '/')) {
    $params1 = explode('.', substr($_SERVER['REQUEST_URI'], 1));
    $params2 = explode('?', $params1[1]);
    $params3 = explode('&', $params2[1]);
    $page = $params1[0];
    $format = $params2[0];
    
    // Create out own half-ass version of $_GET 
    $getvars = array();
    foreach ($params3 as $getvar) {
        $arr = explode('=', $getvar);
        $getvars[$arr[0]] = $arr[1];
    }

    // If we get a set language in the URL, use it, otherwise guess from the headers
    if (isset($getvars["lang"]) && preg_match('/([A-Za-z][A-Za-z]|[A-Za-z][A-Za-z]-[A-Za-z][A-Za-z])/',$getvars["lang"])) {
        $i18n = new Strings();
        $i18n->languageCode = substr($getvars["lang"], 0, 2);
    }
    else {
        $i18n = new Strings($_SERVER['HTTP_ACCEPT_LANGUAGE']);
    }
    
    $data['langcode'] = $i18n->languageCode;
    
    $output = new Template();
    $output->strings = &$i18n;
    $output->render($page, $format, $data);
}
else {
    
    // Redirect to a valid path
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: http://$_SERVER[HTTP_HOST]/ip.html");
}

// Timing debug function
function utime (){
    $time = explode( " ", microtime());
    $usec = (double)$time[0];
    $sec = (double)$time[1];
    return $sec + $usec;
}

 // Timing debug
if (DEBUG) {
  $end = utime();
  $run = $end - $start;
  echo "Page created in: " . substr($run, 0, 10) . " seconds.";
}

?>