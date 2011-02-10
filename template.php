<?php
class Template {
    
    public $strings = '';
    public $rendered = false;
    public $data = array();
    
    public function render($page, $extension)
    {   
        $this->_checkStatic($page, $extension);
        if (!$this->rendered) {
            $this->_renderTemplate($page,$extension,&$data);
        }
    }
    
    private function _checkStatic($page, $extension) 
    {
        $filename = STATICPATH.'/'.$page.'.'.$extension;
        if (file_exists($filename)) {
            $this->_sendHeaders($filename,$this->_getContentType($extension),STATICCACHETIME);
            $this->_outputStatic($filename);
            return $this->rendered;
        }
        else {
            return false;
        }
    }

    private function _renderTemplate($page, $extension) 
    {
        $filename = TEMPLATEPATH.'/'.$page.'.'.$extension;
        if (file_exists($filename)) {
            $contenttype = $this->_getContentType($extension);
            $this->_sendHeaders($filename,$contenttype,CACHETIME);
            $data['page'] = $page;
            $this->_outputDynamic($filename);
            return $this->rendered;
        }
        else {
            header("HTTP/1.1 404 Not Found");
            $contenttype = $this->_getContentType('html');
            $this->_sendHeaders('404',$contenttype,STATICCACHETIME);
            $this->_outputDynamic(TEMPLATEPATH.'/404.html');
        }
    }

    private function _sendHeaders($filename, $contenttype, $cache)
    {
        header("Content-Type: ".$contenttype);
        
        if (is_int($cache)) {
            header("Pragma: public");
            header("Cache-Control: maxage=".$cache);
            header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$cache) . ' GMT');
        }
    }

    private function _outputStatic($filename)
    {
        $fp = fopen($filename, 'rb');
        fpassthru($fp);
        $this->rendered = true;
    }
    
    private function _outputDynamic($filename)
    {
        $output = $this->_loadAllIncludes(file_get_contents($filename, false));
        $output = $this->_replaceData($output);
        $output = $this->_replaceTranslations($output);

        print $output;
        $this->rendered = true;
    }
    
    private function _findIncludes($template)
    {
      preg_match_all('/(\{\%\s+include\s+\'[a-zA-Z\d_-]+\.\w+\'\s+\%\})/i',$template,$includes);
      return $includes[0];
    }
    
    private function _findTranslations($template)
    {
      preg_match_all('/(\[\[\w+\]\])/i',$template,$translations);
      return $translations[0];
    }
    
    private function _findData($template)
    {
      preg_match_all('/(\{\{\w+\}\})/i',$template,$data);
      return $data[0];
    }
    
    private function _replaceData($template)
    {
        foreach ($this->_findData($template) as $key) {
          $key = trim($key, '{}');
          if ($this->data[$key] != '') {
            //echo $this->data['langcode'];
            $template = str_replace('{{'.$key.'}}',$this->data[$key],$template);
          }
        }
        
        return $template;
    }
    
    private function _replaceTranslations($template)
    {
      foreach ($this->_findTranslations($template) as $trans) {
        $trans = trim($trans, '[]');
        $template = str_replace('[['.$trans.']]',$this->strings->t($trans),$template);
      }
      
      return $template;
    }
    
    private function _loadAllIncludes($template)
    {
      $includes = $this->_findIncludes($template);
      foreach ($includes as $include) {   
        $include_data = $this->_loadInclude($include);
        if (count($this->_findIncludes($include_data)) > 0) {
          $include_data = $this->_loadAllIncludes($include_data);
        }
        $template = str_replace($include,$include_data,$template);
      }
      
      return $template;
    }
    
    private function _loadInclude($filename)
    {
      $sanitized_filename = preg_replace('/(\{\%\s+include\s+\')/i','',$filename);
      $sanitized_filename = preg_replace('/(\'\s+\%\})/i','',$sanitized_filename);
      $include_filename = TEMPLATEPATH.'/'.$sanitized_filename;
      $include_data = file_get_contents($include_filename, false);
      return $include_data;
    }
    
    private function _getContentType($extension)
    {
        switch ($extension)
        {
            case css:
                return 'text/css';
                break;
                
            case html:
                return 'text/html';
                break;
                
            case xml:
                return 'application/xml';
                break;
                
            case txt:
                return 'text/plain';
                break;
                
            case json:
                return 'application/json';
                break;
                
            default:
                return 'text/plain';
                break;
        }
        
    }
}