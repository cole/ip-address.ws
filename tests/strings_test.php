<?php
include 'test_configuration.php';
require dirname(__FILE__).'/../includes/strings.php';

class StringsTest extends PHPUnit_Framework_TestCase
{
    public function testFallback()
    {
        // assumes fallback is set to 'en'...
        $strings = new Strings('','');
        
        $this->assertEquals(VERSION, $strings->t('VERSION'));
        $this->assertEquals('English', $strings->t('langcode'));
        $this->assertEquals('Oops!', $strings->t('404'));
    }
    
    
    public function testSimpleExplicit()
    {
        $strings = new Strings('ar','');
        
        $this->assertEquals('ar', $strings->language_code);
        $this->assertEquals(VERSION, $strings->t('VERSION'));
        $this->assertEquals('Arabic', $strings->t('langcode'));
        $this->assertEquals("سلسلة لديك قبول اللغة :", $strings->t('language'));
    }
    
    public function testRegionalExplicit()
    {
        $strings = new Strings('es-MX','');
        
        $this->assertEquals('es-MX', $strings->language_code);
        $this->assertEquals('Spanish', $strings->t('langcode'));
        $this->assertEquals("Su cadena de agente de usuario es:", $strings->t('useragent'));
    }
    
    public function testSimpleAcceptLang()
    {
        $strings = new Strings('','en');
        $this->assertEquals('English', $strings->t('langcode'));
        $this->assertEquals("Your IP address is:", $strings->t('ip'));
    }
    
    public function testComplexAcceptLang()
    {
        $strings = new Strings('','en-ca,en;q=0.8,en-us;q=0.6,de-de;q=0.4,de;q=0.2');
        $this->assertEquals('English', $strings->t('langcode'));
        $this->assertEquals("Your IP address is:", $strings->t('ip'));
    }
    
    public function testComplexAcceptLangTwo()
    {
        $strings = new Strings('','en;q=0.8,en-us;q=0.6,de-de;q=0.4,de;q=0.2,ro;q=1.0');
        $this->assertEquals('Romanian', $strings->t('langcode'));
        $this->assertEquals("Adresa dvs. de IP este:", $strings->t('ip'));
    }
    
    public function testBogusInput() {
        $strings = new Strings('qqqew*3','432543jlhasdgfasd');
        $this->assertEquals('English', $strings->t('langcode'));
        $this->assertEquals("Your IP address is:", $strings->t('ip'));
    }
    
    public function testUnknownLanguage() {
        $strings = new Strings('kl','');
        $this->assertEquals('English', $strings->t('langcode'));
        $this->assertEquals("Your IP address is:", $strings->t('ip'));
    }
    
    public function testFallbackForUnknownStrings()
    {
        $strings = new Strings('ro','');
        $this->assertEquals('Romanian', $strings->t('langcode'));
        $this->assertEquals("Adresa dvs. de IP este:", $strings->t('ip'));
        $this->assertEquals('Oops!', $strings->t('404'));
    }

    public function testSimpleProcess()
    {
        $strings = new Strings('en','');
        $raw = "{{_i langcode}}";
        $processed = $strings->process($raw);
        $this->assertEquals('English', $processed);
    }
    
    public function testComplexProcess()
    {
        $strings = new Strings('es', '', false);
        $raw = "{{_i language}} 1234 {{_i version}} Blah Blah Blah {{_i ip}} {{notme}}";
        $processed = $strings->process($raw);
        $expected = "Su cadena de aceptar el lenguaje es: 1234 ".VERSION." Blah Blah Blah Su dirección IP es: {{notme}}";
        $this->assertEquals($expected, $processed);
    }
    
    
    public function testOutputBuffering()
    {
        $strings = new Strings('en', '', true);
        $raw = "{{_i browser}}";
        echo $raw;
        $processed = ob_get_contents();
        $expected = utf8_decode('Your browser appears to be:');
        $this->assertEquals(utf8_encode($expected), utf8_encode($processed));
    }
    
}