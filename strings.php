<?php
class Strings {
    public $languageCode = '';

    // Localization strings
    private $messages = array (
      'default' => array('title' => 'IP-address.ws', 'langcode' => 'Default', 'ip' => 'Your IP address is:', 'hostname' => 'Your hostname is:', 'language' => 'Your accept language string is:', 'useragent' => 'Your user agent string is:', 'browser' => 'Your browser appears to be:', '404' => 'Oops!', 'about' => 'About'),
      
        'ar' => array(
          'langcode' => 'Arabic', 
          'ip' => 'عنوان بروتوكول الإنترنت هو', 
          'hostname' => 'اسم المضيف الخاص بك هو :', 
          'language' => 'سلسلة قبول اللغة الخاصة بك هو :', 
          'useragent' => 'وكيل المستخدم الخاص بك هو سلسلة :', 
          'browser' => 'متصفحك يبدو أن:'),
        'bg' => array(
          'langcode' => 'Bulgarian', 
          'ip' => 'Вашият IP адрес е:', 
          'hostname' => 'Вашият хост е:', 
          'language' => 'ви приеме език поредицата е:', 
          'useragent' => 'вашето потребителско агент стринг е:', 
          'browser' => 'Вашият браузър изглежда е:', '404' => ' Опа! '),
        'ca' => array('langcode' => 'Catalan', 'ip' => 'La seva adreça IP és:', 'hostname' => 'El nom de host és:', 'language' => 'L\'idioma acceptat cadena és', 'useragent' => 'El seu agent d\'usuari de la cadena és:', 'browser' => 'El seu navegador sembla ser: ', '404' => 'Vaja!'),
        'cs' => array('langcode' => 'Czech', 'ip' => 'Vaše IP adresa je:', 'hostname' => 'Vaše jméno je:', 'language' => 'Váš jazyk akceptovat řetězec:', 'useragent' => 'Your user agent řetězec:', 'browser' => 'Váš prohlížeč zdá být:', '404' => ' Jejda! '),
        'da' => array('langcode' => 'Danish', 'ip' => 'Din IP-adresse er:', 'hostname' => 'Dit værtsnavn er:', 'language' => 'Din acceptere sprog strengen er:', 'useragent' => ' Din bruger agent-strengen er:', 'browser' => ' Din browser ser ud til at være:', '404' => 'Ups!'),
        'de' => array('langcode' => 'German', 'ip' => 'Ihre IP-Adresse ist:', 'hostname' => 'Ihr Hostname lautet:', 'language' => 'Ihre Sprache akzeptieren String ist:', 'useragent' => 'Ihre User-Agent-String ist:', 'browser' => 'Ihr Browser scheint werden:', '404' => 'Oops!'),
        'el' => array('langcode' => 'Greek', 'ip' => 'Η διεύθυνση IP σας είναι:', 'hostname' => 'Το όνομα του είναι:', 'language' => 'string γλώσσα σας δεχτεί είναι:', 'useragent' => 'Ο χρήστης πράκτορα συμβολοσειρά είναι:', 'browser' => 'Το πρόγραμμά σας περιήγησης φαίνεται να να είναι:', '404' => 'Ωχ!'),
        'en' => array('langcode' => 'English', 'ip' => 'Your IP address is:', 'hostname' => 'Your hostname is:', 'language' => 'Your accept language string is:', 'useragent' => 'Your user agent string is:', 'browser' => 'Your browser appears to be:', '404' => 'Oops!', 'about' => 'About'),
        'es' => array('langcode' => 'Spanish', 'ip' => 'Su dirección IP es:', 'host' => 'El nombre de host es:', 'language' => 'El idioma aceptado cadena es:', 'useragent' => 'Su agente de usuario de la cadena es:', 'browser' => 'Su navegador parece ser:', '404' => '¡Vaya!'),
        'et' => array('langcode' => 'Estonian', 'ip' => 'IP-aadress on:', 'hostname' => 'Your hostname on:', 'language' => 'Sinu nõustuda keel string on:', 'useragent' => 'Your user agent string on:', 'browser' => 'Sinu veebisirvija tundub on:', '404' => 'Vabandust!'),
        'fa' => array('langcode' => 'Persian', 'ip' => 'آدرس آی. پی شما است'),
        'fi' => array('langcode' => 'Finnish', 'ip' => 'IP-osoite on:', 'hostname' => 'Sinun isäntänimi on:', 'languague' => 'Sinun hyväksyä kielen merkkijono on:', 'useragent' => 'Your User Agent String on:', 'browser' => 'Selaimesi näyttää on:', '404' => 'Oho!'),
        'fr' => array('langcode' => 'French', 'ip' => 'Votre adresse IP est:', 'hostname' => 'Votre nom d\'hôte est:', 'language' => 'Accepter la langue de votre chaîne de caractères est:', 'useragent' => 'Votre user agent string est:', 'browser' => 'Votre navigateur semble être:', '404' => 'Oops!'),
        'gl' => array('langcode' => 'Galician', 'ip' => 'O seu enderezo IP é:', 'hostname' => 'O seu nome é:', 'language' => 'A súa lingua aceptar cadea é:', 'useragent' => 'Your user agent cadea é:', 'browser' => 'O teu navegador semella estar ser:', '404' => 'Uups!'),
        'he' => array('langcode' => 'Hebrew', 'ip' => 'כתובת ה-IP שלך היא'),
        'hi' => array('langcode' => 'Hindi', 'ip' => 'आपके IP पता है:', 'hostname' => 'आपका होस्टनाम है:', 'language' => 'भाषा स्ट्रिंग स्वीकार तुम्हारा है:', 'useragent' => 'आपका उपयोगकर्ता एजेंट स्ट्रिंग है:', 'browser' => 'आपका ब्राउज़र करने के लिए प्रकट होता है हो:', '404' => 'अरे!'),
        'hr' => array('langcode' => 'Croatian', 'ip' => 'Vaša IP adresa je:', 'hostname' => 'Your hostname je:', 'language' => 'Vaše prihvaćanje jezik je string:', 'useragent' => 'Vaša korisnik agent vrpca je:', 'browser' => 'Čini se da Vaš preglednik se: ', '404 '=>' Ups! '),
        'hu' => array('langcode' => 'Hungarian', 'ip' => 'Az Ön IP címe:'),
        'id' => array('langcode' => 'Indonesian', 'ip' => 'Alamat IP Anda adalah:'),
        'it' => array('langcode' => 'Italian', 'ip' => 'Il tuo indirizzo IP è:'),
        'ja' => array('langcode' => 'Japanese', 'ip' => 'あなたのIPアドレスです：'),
        'ko' => array('langcode' => 'Korean', 'ip' => '당신의 IP 주소입니다 :'),
        'lt' => array('langcode' => 'Lithuanian', 'ip' => 'Jūsų IP adresas:'),
        'lv' => array('langcode' => 'Latvian', 'ip' => 'Jūsu IP adrese:'),
        'mt' => array('langcode' => 'Maltese', 'ip' => 'Your IP address huwa:'),
        'nl' => array('langcode' => 'Dutch', 'ip' => 'Uw IP adres is:', 'hostname' => 'Uw hostnaam is:', 'language' => 'Uw taal accepteren string:', 'useragent' => 'Uw user-agent string is:', 'browser' => 'Uw browser lijkt te worden:', '404' => 'Oeps!'),
        'no' => array('langcode' => 'Norwegian', 'ip' => 'Din IP adresse er:'),
        'pl' => array('langcode' => 'Polish', 'ip' => 'Twój adres IP to:'),
        'pt' => array('langcode' => 'Portugese', 'ip' => 'Seu endereço IP é:'),
        'ro' => array('langcode' => 'Romanian', 'ip' => 'Adresa dvs. de IP este:'),
        'ru' => array('langcode' => 'Russian', 'ip' => 'Ваш IP адрес:'),
        'sk' => array('langcode' => 'Slovak', 'ip' => 'Vaša IP adresa je:'),
        'sl' => array('langcode' => 'Slovenian', 'ip' => 'Vaš IP naslov je:'),
        'sq' => array('langcode' => 'Albanian',  'ip' => 'Adresa IP e juaj është:', 'hostname' => 'Your hostname eshte:', 'language' => 'Your pranojnë gjuhën string është:', 'useragent' => 'Your user agent string është:', 'browser' => 'Your browser duket të jetë:', '404' => 'Na falni!'),
        'sr' => array('langcode' => 'Serbian',  'ip' => 'Ваша ИП адреса је:'),
        'sv' => array('langcode' => 'Swedish',  'ip' => 'Din IP adress är:'),
        'th' => array('langcode' => 'Thai',  'ip' => 'ที่อยู่ IP ของท่านคือ:'),
        'tr' => array('langcode' => 'Turkish',  'ip' => 'IP adresi:'),
        'uk' => array('langcode' => 'Ukranian', 'ip' => 'Ваш IP адреса:'),
        'vi' => array('langcode' => 'Vietnamese', 'ip' => 'Địa chỉ IP của bạn là:'),
        'zh' => array('langcode' => 'Chinese', 'ip' => '您的IP地址是:', 'hostname' => '您的主机名是：', 'language' => '你接受语言的字符串是：', 'useragent' => '您的用户代理字符串是：', 'browser' => '您的浏览器是：', '404' => '哎呀！')
        );
        

    public function __construct($httpacceptlang = '')
    {                
        $this->languageCode = $this->_parseAcceptLang($httpacceptlang);
    }
    
    private function _parseAcceptLang($acceptLang) 
    {
        $langs = array();
        
        if ($acceptLang != '') {
            // break up string into pieces (languages and q factors)
            preg_match_all('/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $acceptLang, $langParse);

            if (count($langParse[1])) {
                // create a list like "en" => 0.8
                $langs = array_combine($langParse[1], $langParse[4]);

                // set default to 1 for any without q factor
                foreach ($langs as $lang => $val) {
                    if ($val === '') $langs[$lang] = 1;
                }

                // sort list based on value	
                arsort($langs, SORT_NUMERIC);
            }
            
            // check each languange in turn.  
            foreach ($langs as $lang => $val) {
                $lang = substr($lang, 0, 2); // Only check the first two chars as we don't care about region
                $choice = array_key_exists($lang,$this->messages);
                if ($choice === TRUE) {
                    return $lang;
                    break;
                }
            }
            return 'en'; //nothing found, return English.
            break;
        }
        else {
            return 'en'; //no language requested, return English.
        }
    }
    
    public function t($key, $lang = '') 
    {
        if ($lang === '') {
            $lang = $this->languageCode;
        }
        
        if (isset($this->messages[$lang][$key])) {
            return $this->messages[$lang][$key];
        }
        else if (isset($this->messages['default'][$key])) {
            return $this->messages['default'][$key];
        }
        else {
            error_log("Translation error: LANG: "."$lang, message: '$key'");
        }
    }
}
?>
