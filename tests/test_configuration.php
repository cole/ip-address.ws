<?php

define("VERSION","0.7");

$time = explode( " ", microtime());
$request_time = (double)$time[1] + (double)$time[0];

$_SERVER = array(
  'APPLICATION_ENV' => 'unittest',
  'SERVER_NAME' => 'localhost',
  'REQUEST_URI' => '',
  'REQUEST_TIME' => $request_time
);

ob_start();
