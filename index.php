<?php 

/* IP-address.ws v0.5
    © 2009 Cole Maclean
    TODO: build out template system - remove some duplication
*/

/*
 //Timing debug
function utime (){
    $time = explode( " ", microtime());
    $usec = (double)$time[0];
    $sec = (double)$time[1];
    return $sec + $usec;
}
$start = utime();
 // End timing debug 
 */
 
require 'strings.php';

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
    
    // OK, ready to choose a format
    switch ($format) { 
        
        case txt:
            switch ($page) {
                case ip:
                    header('Content-Type: text/plain');
                    echo $_SERVER['REMOTE_ADDR'];
                    break;

                case hostname:
                    header('Content-Type: text/plain');
                    echo gethostbyaddr($_SERVER['REMOTE_ADDR']);
                    break;

                case language:
                    header('Content-Type: text/plain');
                    echo $_SERVER['HTTP_ACCEPT_LANGUAGE'];
                    break;

                case useragent:
                    header('Content-Type: text/plain');
                    echo $_SERVER['HTTP_USER_AGENT'];
                    break;
                
                default:
                    header("HTTP/1.1 404 Not Found");
                    header('Content-Type: text/plain');
                    echo $i18n->t('404').' 404';
                    break;
            }
        break;

        case xml:
            switch ($page) {
                case ip:
                    header('Content-Type: application/xml');
                    echo "<?xml version=\"1.0\" encoding=\"utf-8\"?".">\n";
                    echo "<data>".$_SERVER['REMOTE_ADDR']."</data>";
                    break;

                case hostname:
                    header('Content-Type: application/xml');
                    echo "<?xml version=\"1.0\" encoding=\"utf-8\"?".">\n";
                    echo "<data>".gethostbyaddr($_SERVER['REMOTE_ADDR'])."</data>";
                    break;

                case language:
                    header('Content-Type: application/xml');
                    echo "<?xml version=\"1.0\" encoding=\"utf-8\"?".">\n";
                    echo "<data>".$_SERVER['HTTP_ACCEPT_LANGUAGE']."</data>";
                    break;

                case useragent:
                    header('Content-Type: application/xml');
                    echo "<?xml version=\"1.0\" encoding=\"utf-8\"?".">\n";
                    echo "<data>".$_SERVER['HTTP_USER_AGENT']."</data>";
                    break;
                
                case 404:
                default:
                    header("HTTP/1.1 404 Not Found");
                    header('Content-Type: application/xml');
                    echo "<?xml version=\"1.0\" encoding=\"utf-8\"?".">\n";
                    echo "<data>".$i18n->t('404').' 404'."</data>";
                    break;
            }
        break;

        case json:
            switch ($page) {

                case ip:
                    header('Content-Type: application/json');
                    echo "{\n".$_SERVER['REMOTE_ADDR']."\n}";
                    break;

                case hostname:
                    header('Content-Type: application/json');
                    echo "{\n".gethostbyaddr($_SERVER['REMOTE_ADDR'])."\n}";
                    break;

                case language:
                    header('Content-Type: application/json');
                    echo "{\n".$_SERVER['HTTP_ACCEPT_LANGUAGE']."\n}";
                    break;

                case useragent:
                    header('Content-Type: application/json');
                    echo "{\n".$_SERVER['HTTP_USER_AGENT']."\n}";
                    break;

                case 404:
                    
                default:
                    header("HTTP/1.1 404 Not Found");
                    header('Content-Type: application/json');
                    echo "{\n".$i18n->t('404').' 404'."\n}";
                    break;
            }
        break;
        
        case css:
            switch ($page) {
                
                case iphone:
                header('Content-Type: text/css');
                print <<<END
body { padding: 0;
   margin: 0;
   background-color: #E0E0E0;
  }
.box { color: #333333;
  background-color: #E0E0E0;
  margin: 0;
  padding: 20px 20px;
  border: none;
}
.preamble { font-family: 'Helvetica Neue',Helvetica,Verdana,Arial,sans-serif;
        font-size: 20px;
		font-style: italic;
		font-weight: lighter;
		text-shadow: #AAAAAA 2px 1px 1px;
		word-wrap: break-word;
	   }
.data { font-size: 40px;
  font-weight: bold;
  font-family: 'Helvetica Neue',Helvetica,Verdana,Arial,sans-serif;
  text-align: center;
  word-wrap: break-word;
}
.footer {
font-family: 'Helvetica Neue',Helvetica,Verdana,Arial,sans-serif;
font-size: 10px;
font-weight: lighter;
color: #666666;
width: 100%;
text-align: center;
}
.footer a:link, .footer a:visited {
color: #666666;
text-decoration: none;
}
.footer a:active, .footer a:hover {
color: #666666;
text-decoration: none;
background-color: #E0E0E0;
}
END;
                break;
                
                case screen:
                header('Content-Type: text/css');
                print <<<END
body {  font-family: 'Helvetica Neue',Helvetica,Verdana,Arial,sans-serifserif;
    font-size: 1em;
}
a, ul.about li { font-weight: 400;
}
a:link, a:visited { color: #666666;
                text-decoration: none;
}

a:active, a:hover { color: #999999;
                text-decoration: none;
                background-color: #FFFFFF;
}
h1 { font-size: 30px;
}
h2 { font-size: 18px;
}
ul { text-decoration: none;
 list-style: none;
 line-height: 1.2em;
}
li {
font-size: 15px;
}
.box { color: #333333;
  background-color: #E0E0E0;
  margin: 150px auto 150px auto;
  padding: 20px 40px;
  max-width: 600px;
  min-width: 500px;
  border: 2px solid #AAAAAA;
  border-radius: 10px;
  -moz-border-radius: 10px;
  -webkit-border-radius: 10px;
  -o-border-radius: 10px;
  -icab-border-radius: 10px;
  -khtml-border-radius: 10px;
  -webkit-box-shadow: 4px 3px 3px #666666;
  -moz-box-shadow: 4px 3px 3px #666666;
  box-shadow: 4px 3px 3px #666666;
}
.preamble { font-family: 'Helvetica Neue',Helvetica,Verdana,Arial,sans-serif;
        font-size: 20px;
		font-style: italic;
		font-weight: lighter;
		text-shadow: #AAAAAA 2px 1px 1px; 
}
.data { font-size: 80px;
  font-weight: bold;
  font-family: 'Helvetica Neue',Helvetica,Verdana,Arial,sans-serif;
  text-align: center;
  word-wrap: break-word;
}
.footer {
font-family: 'Helvetica Neue',Helvetica,Verdana,Arial,sans-serif;
font-size: 10px;
font-weight: lighter;
color: #666666;
width: 100%;
text-align: center;
}
.footer a:link, .footer a:visited {
color: #666666;
text-decoration: none;
}
.footer a:active, .footer a:hover {
color: #666666;
text-decoration: none;
background-color: #E0E0E0;
}
END;
                break;
                
                case 404:
                    
                default:
                    header("HTTP/1.1 404 Not Found");
                    header('Content-Type: text/plain');
                    echo $i18n->t('404').' 404';
                    break;
            }
        break;
        
        case html:
            switch ($page) {

                case ip:
                    $data = $_SERVER['REMOTE_ADDR'];
                    $host = $_SERVER['HTTP_HOST'];
                    $preamble = $i18n->t('ip');
                    $about = $i18n->t('about');
                    print <<<HTML
<!DOCTYPE html>
<html lang="$i18n->languageCode">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no" />
    <meta name="format-detection" content="telephone=no" />
    <title>IP-address.ws</title>
    <link rel="stylesheet" type="text/css" media="screen and (min-device-width: 600px)" href="screen.css" />
    <link rel="stylesheet" type="text/css" media="handheld, screen and (max-device-width: 480px)" href="iphone.css" />
    <!-- Curious about this page?  Visit http://$host/about.html -->
</head>
<body>
    <div class="box">
                <span class="preamble">&nbsp;&nbsp;$preamble</span>
                <br/>
                <span class="data">$data</span>
                <br/>
    </div>
</body>
</html>
HTML;
                    break;

                case hostname:
                    $data = gethostbyaddr($_SERVER['REMOTE_ADDR']);
                    $host = $_SERVER['HTTP_HOST'];
                    $preamble = $i18n->t('hostname');
                    $about = $i18n->t('about');
                    print <<<HTML
<!DOCTYPE html>
<html lang="$i18n->languageCode">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no" />
    <meta name="format-detection" content="telephone=no" />
    <title>IP-address.ws</title>
    <link rel="stylesheet" type="text/css" media="screen and (min-device-width: 600px)" href="screen.css" />
    <link rel="stylesheet" type="text/css" media="handheld, screen and (max-device-width: 480px)" href="iphone.css" />
    <!-- Curious about this page?  Visit http://$host/about.html -->
</head>
<body>
    <div class="box">
                <span class="preamble">&nbsp;&nbsp;$preamble</span>
                <br/>
                <span class="data">$data</span>
                <br/>
    </div>
</body>
</html>
HTML;
                    break;

                case language:
                    $data = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
                    $host = $_SERVER['HTTP_HOST'];
                    $preamble = $i18n->t('language');
                    $about = $i18n->t('about');
                    print <<<HTML
<!DOCTYPE html>
<html lang="$i18n->languageCode">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no" />
    <meta name="format-detection" content="telephone=no" />
    <title>IP-address.ws</title>
    <link rel="stylesheet" type="text/css" media="screen and (min-device-width: 600px)" href="screen.css" />
    <link rel="stylesheet" type="text/css" media="handheld, screen and (max-device-width: 480px)" href="iphone.css" />
    <!-- Curious about this page?  Visit http://$host/about.html -->
</head>
<body>
    <div class="box">
                <span class="preamble">&nbsp;&nbsp;$preamble</span>
                <br/>
                <span class="data">$data</span>
                <br/>
    </div>
</body>
</html>
HTML;
                    break;

                case useragent:
                    $data = $_SERVER['HTTP_USER_AGENT'];
                    $host = $_SERVER['HTTP_HOST'];
                    $preamble = $i18n->t('useragent');
                    $about = $i18n->t('about');
                    print <<<HTML
<!DOCTYPE html>
<html lang="$i18n->languageCode">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no" />
    <meta name="format-detection" content="telephone=no" />
    <title>IP-address.ws</title>
    <link rel="stylesheet" type="text/css" media="screen and (min-device-width: 600px)" href="screen.css" />
    <link rel="stylesheet" type="text/css" media="handheld, screen and (max-device-width: 480px)" href="iphone.css" />
    <!-- Curious about this page?  Visit http://$host/about.html -->
</head>
<body>
    <div class="box">
                <span class="preamble">&nbsp;&nbsp;$preamble</span>
                <br/>
                <span class="data">$data</span>
                <br/>
    </div>
</body>
</html>
HTML;
                break;
                
                case about:
                    $host = $_SERVER['HTTP_HOST'];
                    print <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no" />
    <meta name="format-detection" content="telephone=no" />
    <title>IP-address.ws - About</title>
    <link rel="stylesheet" type="text/css" media="screen and (min-device-width: 600px)" href="screen.css" />
    <link rel="stylesheet" type="text/css" media="handheld, screen and (max-device-width: 480px)" href="iphone.css" />
    <!-- Curious about this page?  Visit http://$host/about.html -->
</head>
<body>
    <div class="box">
        <h1>About this site</h1>
        <p><a href="http://$host/">IP-address.ws</a> helps find your <a href="http://en.wikipedia.org/wiki/IP_address">IP address</a>.</p>
        <h2>Other HTTP request details<h2>
        <ul class="about">
            <li><a href="http://$host/ip.html">IP address</a> (<a href="http://$host/ip.xml">XML</a>, <a href="http://$host/ip.txt">Text</a>, <a href="http://$host/ip.json">JSON</a>)</li>
            <li><a href="http://$host/hostname.html">Hostname</a> (<a href="http://$host/hostname.xml">XML</a>, <a href="http://$host/hostname.txt">Text</a>, <a href="http://$host/hostname.json">JSON</a>)</li>
            <li><a href="http://$host/language.html">Accept language</a> (<a href="http://$host/language.xml">XML</a>, <a href="http://$host/language.txt">Text</a>, <a href="http://$host/language.json">JSON</a>)</li>
            <li><a href="http://$host/useragent.html">Useragent</a> (<a href="http://$host/useragent.xml">XML</a>, <a href="http://$host/useragent.txt">Text</a>, <a href="http://$host/useragent.json">JSON</a>)</li>
        </ul>
    </div>
</body>
</html>
HTML;

                    break;
                    
                case 404:
                default:
                    header("HTTP/1.1 404 Not Found");
                    $host = $_SERVER['HTTP_HOST'];
                    $preamble = $i18n->t('404');
                    $about = $i18n->t('about');
                    print <<<HTML
<!DOCTYPE html>
<html lang="$i18n->languageCode">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no" />
    <meta name="format-detection" content="telephone=no" />
    <title>IP-address.ws</title>
    <link rel="stylesheet" type="text/css" media="screen and (min-device-width: 600px)" href="screen.css" />
    <link rel="stylesheet" type="text/css" media="handheld, screen and (max-device-width: 480px)" href="iphone.css" />
    <!-- Curious about this page?  Visit http://$host/about.html -->
</head>
<body>
    <div class="box">
                <span class="preamble">&nbsp;&nbsp;$preamble</span>
                <br/>
                <span class="data">404</span>
                <br/>
    </div>
</body>
</html>
HTML;
                    break;
            }
        break;
            
        default:
            // HTML 404 if the format isn't valid
            header("HTTP/1.1 404 Not Found");
            $host = $_SERVER['HTTP_HOST'];
            $preamble = $i18n->t('404');
            $about = $i18n->t('about');
            print <<<HTML
<!DOCTYPE html>
<html lang="$i18n->languageCode">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no" />
    <meta name="format-detection" content="telephone=no" />
    <title>IP-address.ws</title>
    <link rel="stylesheet" type="text/css" media="screen and (min-device-width: 600px)" href="screen.css" />
    <link rel="stylesheet" type="text/css" media="handheld, screen and (max-device-width: 480px)" href="iphone.css" />
    <!-- Curious about this page?  Visit http://$host/about.html -->
</head>
<body>
    <div class="box">
                <span class="preamble">&nbsp;&nbsp;$preamble</span>
                <br/>
                <span class="data">404</span>
                <br/>
    </div>
</body>
</html>
HTML;
            break;
    }
}
else {
    
    // Redirect to a valid path
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: http://$_SERVER[HTTP_HOST]/ip.html");
}


 // Timing debug
 /*

$end = utime();
$run = $end - $start;
echo "Page created in: " . 
   substr($run, 0, 10) . " seconds.";
*/

?>