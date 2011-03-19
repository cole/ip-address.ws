<?php

require dirname(__FILE__).'/../includes/router.php';
require dirname(__FILE__).'/../includes/strings.php';

$router = new Router('http://localhost/ip.html?lang=es', '');
$router->template_vars = array(
    "ip" => '255.255.255.252',
    "hostname" => 'example.com',
    "language" => 'en-ca,en;q=0.8,en-us;q=0.6,de-de;q=0.4,de;q=0.2',
    "useragent" => 'Mozilla, not IE',
    "host" => 'ip-address.ws',
    'langcode' => '',
    );
    
$i18n = new Strings($router->query_vars['lang'], $router->template_vars['language'], true);

// Set the language code so we can output it in HTML
$router->template_vars['langcode'] = $i18n->language_code;
//var_dump($router->template_vars['langcode']);


$router->render(array($i18n,'flush'));


//var_dump($router->query_vars);
//var_dump($router->strings->t('ip'));