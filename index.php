<?php 

/* IP-address.ws v0.7
© 2011 Cole Maclean
*/

define("VERSION","0.7");

require 'includes/timer.php';
$timer = new Timer();

require 'includes/router.php';
require 'includes/strings.php';

if (isset($_SERVER['REQUEST_URI']))
    $request_uri = $_SERVER['REQUEST_URI'];
else
    $request_uri = '';

$router = new Router($request_uri);

$router->template_vars = array(
    "ip" => '',
    "hostname" => '',
    "language" => '',
    "useragent" => '',
    "host" => '',
    'langcode' => '',
    );

if (isset($_SERVER['REMOTE_ADDR'])) {
    $router->template_vars["ip"] = $_SERVER['REMOTE_ADDR'];
    $router->template_vars["hostname"] = gethostbyaddr($_SERVER['REMOTE_ADDR']);
}

if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
    $router->template_vars["language"] = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
    
if (isset($_SERVER['HTTP_USER_AGENT']))
    $router->template_vars["useragent"] = $_SERVER['HTTP_USER_AGENT'];
    
if (isset($_SERVER['HTTP_HOST']))
    $router->template_vars["host"] = $_SERVER['HTTP_HOST'];

$lang = (isset($router->query_vars['lang'])) ? $router->query_vars['lang'] : '';

$i18n = new Strings($lang, $router->template_vars['language'], true);

// Set the language code so we can output it in HTML
$router->template_vars['langcode'] = $i18n->language_code;

// add timing data
$router->template_vars['timing'] = $timer->results();

// Render the page, with a callback to flush the translation buffer
$router->render(array($i18n,'flush'));
