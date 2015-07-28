<?php

namespace McVod\Framework;

class Response{
  public $statusCodes = array(
      100 => "Continue",
      101 => "Switching Protocols",
      200 => "OK",
      201 => "Created",
      202 => "Accepted",
      203 => "Non-Authoritative Information",
      204 => "No Content",
      205 => "Reset Content",
      206 => "Partial Content",
      300 => "Multiple Choices",
      301 => "Moved Permanently",
      302 => "Found",
      303 => "See Other",
      304 => "Not Modified",
      305 => "Use Proxy",
      306 => "(Unused)",
      307 => "Temporary Redirect",
      400 => "Bad Request",
      401 => "Unauthorized",
      402 => "Payment Required",
      403 => "Forbidden",
      404 => "Not Found",
      405 => "Method Not Allowed",
      406 => "Not Acceptable",
      407 => "Proxy Authentication Required",
      408 => "Request Timeout",
      409 => "Conflict",
      410 => "Gone",
      411 => "Length Required",
      412 => "Precondition Failed",
      413 => "Request Entity Too Large",
      414 => "Request-URI Too Long",
      415 => "Unsupported Media Type",
      416 => "Requested Range Not Satisfiable",
      417 => "Expectation Failed",
      500 => "Internal Server Error",
      501 => "Not Implemented",
      502 => "Bad Gateway",
      503 => "Service Unavailable",
      504 => "Gateway Timeout",
      505 => "HTTP Version Not Supported",
  );

  private $_headers = array();
  private $_bodys   = array();
  private $_statusCode = 200;

  public function __construct($code = null){
    $code = is_null($code)?200: $code;
    $this->_statusCode = $code;
  }

  public function setCode($code){
    if(in_array(intval($code),array_keys($this->statusCodes))){
      $this->_statusCode = $code;
    }else{
      throw new \Exception('Code Not Found',500);
    }
  }

  public function __set($key,$value){
    $this->_bodys[$key] = $value;
  }

  public function __get($key){
    return isset($this->_bodys[$key])?$this->_bodys[$key]:NULL;
  }

  public function setBody($body){
    is_array($body)?$this->_bodys = $body: NULL ;
  }

  public function getBody(){
    return $this->_bodys;
  }

  public function setHeader($header){
    $this->_headers[] = $header;
    header($header);
  }
  public function getHeader(){
    return $this->_headers;
  }

  public function redicrect($url){
    return header('Location:'.$url);
  }

  public function sendResponse(){
    $resArray = array(
      'code' => $this->_statusCode,
      'msg'  => $this->statusCodes[$this->_statusCode],
      'body' => $this->_bodys,
    );
    die(json_encode($resArray,JSON_FORCE_OBJECT));
  }
}
