<?php

include 'test_configuration.php';
require dirname(__FILE__).'/../includes/router.php';

class RouterTest extends PHPUnit_Framework_TestCase
{
    public function basicSetup()
    {
        $router = new Router('http://localhost/ip.html', '');
        $router->template_vars = array(
            "ip" => '255.255.255.252',
            "hostname" => 'example.com',
            "language" => 'en-ca,en;q=0.8,en-us;q=0.6,de-de;q=0.4,de;q=0.2',
            "useragent" => 'Mozilla, not IE',
            "host" => 'ip-address.ws',
            'langcode' => '',
            );
        return $router;
        
    }
    public function testSimpleConstruct()
    {
        $test_router = new Router('http://ip-address.ws/hostname.html');
        $this->assertEquals('/', $test_router->directory);
        $this->assertEquals('hostname', $test_router->page);
        $this->assertEquals('html', $test_router->format);
        $this->assertEquals('/hostname.html', $test_router->filepath);
        $this->assertEquals(0, count($test_router->query_vars));
    }
    
    public function test404()
    {
        $test_router = new Router('http://ip-address.ws/bogus.html');
        $test_router->render();
        $this->assertEquals('404', $test_router->page);
        $this->assertEquals('/404.html', $test_router->filepath);
        
    }
    
    public function testMainPage()
    {
        $router = $this->basicSetup();
        $router->render();
        $this->assertEquals(true,$router->rendered);
    }
    
    public function testContentType()
    {
        $router = $this->basicSetup();
        $this->assertEquals('text/css', $router->getContentType('css'));
        $this->assertEquals('text/html', $router->getContentType('html'));
        $this->assertEquals('text/plain', $router->getContentType());
        
    }
}