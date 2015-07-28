<?php

namespace McVod\Framework;

abstract class Middleware{
  protected $_request = NULL;
  protected $_response = NULL;
  public function __construct($request,$response){
    $this->_request = $request;
    $this->_response = $response;
  }

  public function execute(){
    
  }
}
