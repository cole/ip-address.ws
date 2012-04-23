<?php

/**
* Router
*
* Simple request router and template renderer.
*
* @copyright 2011 Cole Maclean
* @license http://www.opensource.org/licenses/mit-license.php MIT License
* @version 0.2
*/

class Router {

    // Router settings
    public static $dynamic_cache_time = 60;
    public static $default_page = "ip.html";
    public static $base_uri = 'http://ip-address.ws/';
    public static $template_path = 'templates';
    
    public $default_route;
    public $directory = '';
    public $page = '';
    public $format = '';
    public $filepath = '';
    public $query_vars = array();
    public $content_type;
    public $rendered = false;
    public $template_vars;
    
    /**
    * protocol
    *
    * returns the HTTP version being used (usually 1.1)
    * @return string
    */
    public static function protocol() {
        if (!empty($_SERVER['SERVER_PROTOCOL']))
            return $_SERVER['SERVER_PROTOCOL'];
        else
            return 'HTTP/1.0';
    }
    
    /**
    * Constructor
    *
    * @param string $uri Request URI
    * @return Router
    */
    public function __construct($uri) {    
        $this->default_route = Router::$base_uri.Router::$default_page;
        $this->route($uri);
    }

    /**
    * route
    *
    * parse the request URI, sets up the router object
    * @param string $uri Request URI
    * @return void
    */
    public function route($uri) {
        // Route the request - choose page and format based on the URI
        if (($uri === '/') || (empty($uri))) {
            $this->redirect($this->default_route);
            return false;
        }

        $query = parse_url($uri,PHP_URL_QUERY);
        $path = parse_url($uri,PHP_URL_PATH);

        $this->directory = dirname($path);
        $this->format = pathinfo($path, PATHINFO_EXTENSION);
        $this->page = basename($path, '.' . $this->format);
        $this->filepath = '/' . $this->page . '.' . $this->format;
        $this->content_type = $this->getContentType($this->format);
        
        if (!empty($query)) {
            foreach (explode('&', $query) as $getvar) {
                $arr = explode('=', $getvar);
                $this->query_vars[$arr[0]] = $arr[1];
            }
        }

    }
    
    /**
    * render
    *
    * if the request matches a page, output it
    * @param callback $callback Function to execute after rendering
    * @return void
    */
    public function render($callback=null) {
        // 404 if we can't find the template
        if (!file_exists(dirname(__FILE__) . '/../' . Router::$template_path . $this->filepath)) {
            header(Router::protocol() . " 404 Not Found", true, 404); 
            $this->format = 'html';
            $this->content_type = $this->getContentType($this->format);
            $this->filepath = '/404.html';
            $this->page = '404';
        }

        $this->sendContentHeaders($this->content_type, Router::$dynamic_cache_time);
        
        ob_start(array(&$this, 'replaceVariables'));
        ob_start(array(&$this, 'loadAllIncludes'));
        print file_get_contents(dirname(__FILE__).'/../'.Router::$template_path.$this->filepath, false);
        ob_end_flush();
        ob_end_flush();

        $this->rendered = true;
        if (!$callback === null)
            call_user_func($callback);
    }

    /**
    * redirect
    *
    * redirect to another location
    * @param string $location URI to redirect to
    * @return void
    */
    private function redirect($location) {
        header(Router::protocol() . " Moved Permanently", true, 301);
        header("Location: $location");
    }
    
    /**
    * sendContentHeaders
    *
    * send content type and cache headers
    * @param string $content_type Content-type string
    * @param int $cache Cache time in seconds
    * @return void
    */
    private function sendContentHeaders($content_type = 'text/plain', $cache = 0)
    {
        header("Content-Type: ".$content_type);
        if (is_int($cache)) {
            $cache_time = gmdate('D, d M Y H:i:s', time() + $cache);
            header("Pragma: public");
            header("Cache-Control: maxage=".$cache);
            header('Expires: '.$cache_time.' GMT');
        }
    }
    
    /**
    * getContentType
    *
    * returns valid content-type string based on extension given
    * @param string $extension File extension
    * @return string
    */
    private function getContentType($extension)
    {
        switch ($extension)
        {
            case 'css': return 'text/css'; break;
            case 'html': return 'text/html'; break;
            case 'xml': return 'application/xml'; break;
            case 'txt': return 'text/plain'; break;
            case 'json': return 'application/json'; break;
            default: return 'text/plain'; break;
        }
    }
        
    /**
    * replaceVariables
    *
    * replaces all data fields (indicated by '{{ }}') in the given string
    * @param string $output string to output
    * @return string
    */
    private function replaceVariables($output)
    {
        preg_match_all('/(\{\{(\w+)\}\})/i',$output,$template_vars_to_replace);
        foreach ($template_vars_to_replace[2] as $key) {
            $output = preg_replace('/(\{\{'.$key.'\}\})/i',$this->template_vars[$key],$output);
        }
        
        return $output;
    }

    /**
    * findIncludes
    *
    * checks input template for include strings, e.g. '{{> _header }}' 
    * @param string $template Temlate to check
    * @return array
    */
    private function findIncludes($template)
    {
        // Use mustache partials: {{> foo }}
        $partial_regex = '/(\{\{\>\s+([a-zA-Z0-9_-]+(\.\w+)?)\s*\}\})/i';
        preg_match_all($partial_regex, $template, $includes);
        return $includes[2];
    }

    /**
    * loadAllIncludes
    *
    * loads all included files
    * @param string $template Temlate to check for includes
    * @return string
    */
    private function loadAllIncludes($template)
    {
        $includes = $this->findIncludes($template);
        foreach ($includes as $include_name) {   
            if (preg_match('/(\.\w+)/', $include_name) === 0) {
                $filename = $include_name.'.'.$this->format;
            }
            $include_path = dirname(__FILE__).'/../'.Router::$template_path.'/'.$filename;
            $include_data = file_get_contents($include_path, false);
            if (count($this->findIncludes($include_data)) > 0) {
                $include_data = $this->loadAllIncludes($include_data);
            }
            $include_regex = '/(\{\{\>\s+'.$include_name.'\s*\}\})/i';
            $template = preg_replace($include_regex,$include_data,$template);
        }

        return $template;
    }
    
}