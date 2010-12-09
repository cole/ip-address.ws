<?php
class Template {
    
    public $strings = '';
    
    public $rendered = false;
    
    public function render($page, $extension, $data)
    {   
        $this->_checkStatic($page, $extension);
        if (!$this->rendered) {
            $this->_renderTemplate($page,$extension,$data);
        }
    }
    
    private function _checkStatic($page, $extension) 
    {
        $filename = STATICPATH.'/'.$page.'.'.$extension;
        if (file_exists($filename)) {
            $contenttype = $this->_getContentType($extension);
            $this->_sendHeaders($filename,$contenttype,STATICCACHETIME);
            $this->_passthroughStatic($filename);
            return $this->rendered;
        }
        else {
            return false;
        }
    }

    private function _renderTemplate($page, $extension, $data) 
    {
        $filename = TEMPLATEPATH.'/'.$page.'.'.$extension;
        if (file_exists($filename)) {
            $contenttype = $this->_getContentType($extension);
            $this->_sendHeaders($filename,$contenttype,CACHETIME);
            $data['page'] = $page;
            $this->_passthroughDynamic($filename,$data);
            return $this->rendered;
        }
        else {
            header("HTTP/1.1 404 Not Found");
            $contenttype = $this->_getContentType('html');
            $this->_sendHeaders('404',$contenttype,STATICCACHETIME);
            $this->_passthroughDynamic(TEMPLATEPATH.'/404.html',$data);
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

    private function _passthroughStatic($filename)
    {
        $fp = fopen($filename, 'rb');
        fpassthru($fp);
        $this->rendered = true;
    }
    
    private function _passthroughDynamic($filename,$data)
    {
        $output = file_get_contents($filename, false);
        
        preg_match_all('/(\{\%\s+include\s+\'[a-zA-Z\d_-]+\.\w+\'\s+\%\})/i',$output,$includes);
        foreach ($includes[0] as $include) {   
          $sanitized_filename = preg_replace('/(\{\%\s+include\s+\')/i','',$include);
          $sanitized_filename = preg_replace('/(\'\s+\%\})/i','',$sanitized_filename);
          $include_filename = TEMPLATEPATH.'/'.$sanitized_filename;
          $include_data = file_get_contents($include_filename, false);
          $output = str_replace($include,$include_data,$output);
        }
        
        foreach ($data as $key => $str) {
            $output = str_replace('{{'.$key.'}}',$str,$output);
        }
        
        preg_match_all('/(\[\[\w+\]\])/i',$output,$translations);
        foreach ($translations[0] as $trans) {
            $trimmed = trim($trans, '[]');
            $output =  str_replace($trans,$this->strings->t($trimmed),$output);
        }

        print $output;
        $this->rendered = true;
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