<?php

class curl {
  protected $ch;
  protected $result;
  protected $http_code;
  protected $serverTime;
  
  public function start() {
    if (empty($this->ch)) {
      $this->ch = curl_init();
    }
  }
  
  public function close() {
    if (!empty($this->ch)) {
      curl_close($this->ch);
    }
  }
  
  public function setOption($opt, $value) {
    if (!empty($this->ch)) {
      curl_setopt($this->ch, $opt, $value);
    }
  }
  
  public function getInfo($field = null) {
    if (!empty($this->ch)) {
      $info = curl_getinfo($this->ch);
      if ($field == null)
        return $info;
      else
        return $info[$field];
    }
  }
  
  public function execute() {
    if (!empty($this->ch)) {
      $this->result = curl_exec($this->ch);
      $this->http_code = curl::getInfo("http_code");
    }
  }
  
  public function getResult() {
    if (!empty($this->result))
      return $this->result;
  }

  public function getHTTPCode() {
    if (!empty($this->http_code))
      return $this->http_code;
  }
}

?>