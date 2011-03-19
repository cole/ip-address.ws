<?php

// This script updates the locale INI files

$languages = array(
    "Afrikaans" => "af",
    "Albanian" => "sq",
    "Arabic" => "ar",
    "Belarusian" => "be",
	"Bulgarian" => "bg",
    "Catalan" =>	"ca",
    "Chinese Simplified" =>	"zh-CN",
    "Chinese Traditional"	=> "zh-TW",
    "Croatian" =>	"hr",
    "Czech" =>	"cs",
    "Danish" =>	"da",
    "Dutch" =>	"nl",
    "English" =>	"en",
    "Estonian" =>	"et",
    "Filipino" =>	"tl",
    "Finnish" =>	"fi",
    "French" =>	"fr",
    "Galician" =>	"gl",
    "German" =>	"de",
    "Greek" =>	"el",
    "Haitian Creole" =>	"ht",
    "Hebrew" =>	"iw",
    "Hindi" =>	"hi",
    "Hungarian" =>	"hu",
    "Icelandic" =>	"is",
    "Indonesian" =>	"id",
    "Irish" =>	"ga",
    "Italian" =>	"it",
    "Japanese" =>	"ja",
    "Latvian" =>	"lv",
    "Lithuanian" =>	"lt",
    "Macedonian" =>	"mk",
    "Malay" =>	"ms",
    "Maltese" =>	"mt",
    "Norwegian" =>	"no",
    "Persian" =>	"fa",
    "Polish" =>	"pl",
    "Portuguese" =>	"pt",
    "Romanian" =>	"ro",
    "Russian" =>	"ru",
    "Serbian" =>	"sr",
    "Slovak" =>	"sk",
    "Slovenian" =>	"sl",
    "Spanish" =>	"es",
    "Swahili" =>	"sw",
    "Swedish" =>	"sv",
    "Thai" =>	"th",
    "Turkish" =>	"tr",
    "Ukrainian" =>	"uk",
    "Vietnamese" =>	"vi",
    "Welsh" =>	"cy",
    "Yiddish" =>	"yi",
);

$translations = array(
    'ip' => "Your IP address is:",
    'hostname' => "Your hostname is:",
    'language' => "Your accept language string is:",
    'useragent' => "Your user agent string is:",
    'browser' => "Your browser appears to be:",
    '404' => "Oops!",
    'about' => "About"
);

foreach($languages as $long=>$langcode) {
    $output = array();
    $output['langcode'] = $long;
    print "Getting translations for ".$long;
    $path = "./$langcode.ini";
    
    foreach ($translations as $localkey=>$english) { 
        $output[$localkey] = get_google_trans(urlencode($english), $langcode);
    }
    write_ini_file($output, $path, false);
}

function get_google_trans($string, $outputlang, $inputlang='en') {
    $api_key = "AIzaSyCLZ6n68tFPEOk3IZMzSGsRRMUq5-XK0TQ";
    $url = 'https://www.googleapis.com/language/translate/v2?key='.$api_key.'&q='.$string.'&source='.$inputlang.'&target='.$outputlang;
    $json = file_get_contents($url);
    $response = json_decode($json,true);
    return $response['data']['translations'][0]['translatedText'];
}

function write_ini_file($assoc_arr, $path, $has_sections=FALSE) { 
    $content = ""; 
    if ($has_sections) { 
        foreach ($assoc_arr as $key=>$elem) { 
            $content .= "[".$key."]\n"; 
            foreach ($elem as $key2=>$elem2) { 
                if(is_array($elem2)) 
                { 
                    for($i=0;$i<count($elem2);$i++) 
                    { 
                        $content .= $key2."[] = \"".$elem2[$i]."\"\n"; 
                    } 
                } 
                else if($elem2=="") $content .= $key2." = \n"; 
                else $content .= $key2." = \"".$elem2."\"\n"; 
            } 
        } 
    } 
    else { 
        foreach ($assoc_arr as $key2=>$elem) { 
            if(is_array($elem)) 
            { 
                for($i=0;$i<count($elem);$i++) 
                { 
                    $content .= $key2."[] = \"".$elem[$i]."\"\n"; 
                } 
            } 
            else if($elem=="") $content .= $key2." = \n"; 
            else $content .= $key2." = \"".$elem."\"\n"; 
        } 
    } 

    if (!$handle = fopen($path, 'w')) { 
        return false; 
    } 
    if (!fwrite($handle, $content)) { 
        return false; 
    } 
    fclose($handle); 
    return true; 
}