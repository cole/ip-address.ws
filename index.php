<?php 

/* IP-address.ws v0.6
    © 2011 Cole Maclean
*/

require 'config.php';

if (DEBUG) {
  require 'timer.php';
  $timer = new Timer();
}

require 'router.php';
require 'template.php';
require 'strings.php';

$data = array(
    "ip" => $_SERVER['REMOTE_ADDR'],
    "hostname" => gethostbyaddr($_SERVER['REMOTE_ADDR']),
    "language" => $_SERVER['HTTP_ACCEPT_LANGUAGE'],
    "useragent" => $_SERVER['HTTP_USER_AGENT'],
    "host" => $_SERVER['HTTP_HOST'],
    "analytics_code" => ANALYTICS_CODE,
    'langcode' => ''
    );

$router = new Router($_SERVER['REQUEST_URI']);

$i18n = new Strings($router->query_vars[lang],$_SERVER['HTTP_ACCEPT_LANGUAGE']);

// Set the language code so we can output it in HTML
$data['langcode'] = $i18n->language_code;

$output = new Template();
$output->strings = &$i18n;
$output->data = &$data;
$output->render($router->page, $router->format);

if (DEBUG) {
  echo $timer->results();
  echo $i18n->language_code;
}