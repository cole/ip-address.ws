<?php

// Use UTF-8
setlocale(LC_ALL, 'en_US.utf-8');
iconv_set_encoding("internal_encoding", "UTF-8");
mb_internal_encoding('UTF-8');

/**
* Strings
*
* Simple internationalization class.
*
* @copyright 2011 Cole Maclean
* @license http://www.opensource.org/licenses/mit-license.php MIT License
* @version 0.1
*/

class Strings {
    
    // Strings settings
    public static $locale_path = 'locales';
    public static $fallback_language = 'en';

    public $language_code; // String - the language for translations
    
    private $messages = array(); // Array of messages loaded from ini files

    /**
    * Constructor
    *
    * If both params are given, $lang_param takes priority.
    * @param string $lang_param Language code for translations
    * @param string $http_accept_lang Browser accept lang string
    * @param bool $buffer start buffering output for translation?
    * @return Strings
    */
    public function __construct($lang_param = '', $http_accept_lang = '', $buffer=false)
    {   
        if (preg_match('/([A-Za-z]{2}|[A-Za-z]{2}-[A-Za-z]{2})/',
            $lang_param)) {
            $this->language_code = $lang_param;
        }
        else if (!empty($http_accept_lang)) {
            $parsed_accept_lang = $this->parseAcceptLang($http_accept_lang);
            if (!empty($parsed_accept_lang)) {
                $this->language_code = $parsed_accept_lang;
            }
        }
        else {
            $this->language_code = Strings::$fallback_language;
        }
        
        if ($buffer) {
            $this->buffer();
        }
        
        $this->loadMessages($this->language_code);
    }
    
    
    /**
    * buffer
    *
    * Begins output buffering, for automatic translation
    * @return void
    */
    public function buffer()
    {
        ob_start(array($this, 'process'));
    }
    
    /**
    * flush
    *
    * Flushes the buffer.
    * @return void
    */
    public function flush()
    {
        ob_end_flush();
    }
    
    /**
    * t - translate key
    *
    * @param string $key Key for translation
    * @param string $lang OPTIONAL language code (overrides class setting)
    * @return string
    */
    public function t($key, $lang = '') 
    {   
        // Load default language
        if ($lang === '')
            $lang = $this->language_code;
        
        $key = strtolower($key);

        // if key exists
        if ($this->checkKeyExists($key, $lang)) {
            return $this->messages[$lang][$key];
        }
        // messages couldn't be loaded and we have a region
        else if (preg_match('/([A-Za-z]{2}-[A-Za-z]{2})/',$lang)) {
            // see if we have that language without the region
            $lang = substr($lang,0,2);
            if ($this->checkKeyExists($key, $lang))
                return $this->messages[$lang][$key];
        }
        // try fallback language
        else if ($this->checkKeyExists($key, Strings::$fallback_language)) {
            return $this->messages[Strings::$fallback_language][$key];
        }
        // no luck, error out and return untranslated key
        else {
            error_log("Translation error: LANG: $lang, message: '$key'");            
            return "$key"; // Default to key
        }            
    }
    
    /**
    * process
    *
    * Translates all keys enclosed in '{{_i }}' in a string.
    * Uses the class language code.
    * @param string $text text for translation
    * @return string
    */
    public function process($text)
    {
        // for standalone tags, e.g.  {{_i foo}}
        $tag_regex = '/(\{\{_i\s+(\w+)\s*\}\})/i';
        preg_match_all($tag_regex, $text, $translations);

        foreach ($translations[2] as $key) {
            $text = preg_replace('/(\{\{_i\s+'.$key.'\s*\}\})/i', $this->t($key), $text);
        }
        return $text;
    }
    
    /**
    * checkKeyExists
    *
    * Checks for the existance of a given $key for $lang
    * @param string $key Key for translation
    * @param string $lang language code
    * @return bool
    */
    private function checkKeyExists($key, $lang)
    {
        if ((isset($this->messages[$lang])) && (isset($this->messages[$lang][$key])))
            return true;
        else if (($this->loadMessages($lang)) && (isset($this->messages[$lang][$key])))
            return true;
        else
            return false;
    }
    
    /**
    * loadMessages
    *
    * Loads messages for $lang from the ini file into $messages
    * @param string $lang language code
    * @return bool
    */
    private function loadMessages($lang)
    {
        $ini_path = dirname(__FILE__).'/../'.Strings::$locale_path.'/'.$lang.'.ini';
        if (file_exists($ini_path)) {
            $this->messages[$lang] = parse_ini_file($ini_path);
            return true;
        }
        else {
            return false;
        }
    }

    /**
    * parseAcceptLang
    *
    * Determines the preferred language from a browsers accept-language string
    * @param string $accept_lang Accept-langage string
    * @return string
    */
    private function parseAcceptLang($accept_lang) 
    {
        // based on code from Jesse Skinner
        // http://www.thefutureoftheweb.com/blog/use-accept-language-header
        $langs = array();

        if ($accept_lang != '') {
            // break up string into pieces (languages and q factors)
            $regex = '/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i';
            
            preg_match_all($regex, $accept_lang, $lang_parse);

            if (count($lang_parse[1])) {
                // create a list like "en" => 0.8
                $langs = array_combine($lang_parse[1], $lang_parse[4]);

                // set default to 1 for any without q factor
                foreach ($langs as $lang => $val) {
                    if ($val === '') $langs[$lang] = 1;
                }

                // sort list based on value	
                arsort($langs, SORT_NUMERIC);
            }

            // check each languange in turn.  
            foreach ($langs as $lang => $val) {
                if ($this->loadMessages($lang)) {
                    return $lang;
                    break;
                }
                // see if we can find it with just the first two chars
                else if ($this->loadMessages(substr($lang, 0, 2))) {
                    return substr($lang, 0, 2);
                    break;
                }
            }
        }
    }


}