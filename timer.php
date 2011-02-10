<?php
class Timer {
  
  public $requested = 0;
  public $started = 0;
  public $finished = 0;

  public function __construct() {
    $this->started = $this->utime();
    $this->requested = $_SERVER['REQUEST_TIME'];
  }

  public function stop() {
    $this->finished = $this->utime();
  }
  
  public function results() {
    if ($this->finished === 0) {
      $this->stop();
    }
    $since_request = $this->finished - $this->requested;
    $time = $this->finished - $this->started;
    return "Page created in: " . substr($time, 0, 10) . " seconds. "
      . substr($since_request, 0, 10) . " seconds since request was made.";
  }
  
  private function utime() {
      $time = explode( " ", microtime());
      $usec = (double)$time[0];
      $sec = (double)$time[1];
      return $sec + $usec;
  }
}