<?php
class Router {
  
  public $default_route;
  public $base_uri;
  public $page = '';
  public $format = '';
  public $query_vars = array();
  
  public function __construct($uri) {
    $this->base_uri = 'http://'.$_SERVER[HTTP_HOST].dirname($_SERVER[SCRIPT_NAME]);
    $this->default_route = $this->base_uri.'ip.html';
    
    $this->route($uri);
  }
  
  public function route($uri) {
    
    // Route the request - choose page and format based on the URI
    if ($uri === '/') {
      $this->redirect($this->default_route);
      return true;
    }
    
    if (isset($uri)) {
        $params1 = explode('.', substr($uri, 1));
        $params2 = explode('?', $params1[1]);
        $params3 = explode('#', $params2[0]);
        $params4 = explode('&', $_SERVER[QUERY_STRING]);
       // echo "$params1 $params2 $params3 $params4";
        $this->page = $params1[0];
        $this->format = $params3[0];

        foreach ($params4 as $getvar) {
            $arr = explode('=', $getvar);
            $this->query_vars[$arr[0]] = $arr[1];
        }
    }
    else {
        // Redirect to a valid path
        $this->redirect($this->default_route);
    }
    
  }
  
  private function redirect($uri) {
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: $uri");
  }
}