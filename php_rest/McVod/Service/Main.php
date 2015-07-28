<?php
namespace McVod\Service;

use McVod\Framework\Service;

class Main extends Service{
  public function index(){
    $this->_response->setCode(404);
    $this->_response->setBody(array('test1'=>'test'));
  }
}
