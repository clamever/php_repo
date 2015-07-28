<?php

namespace McVod\Service;

use McVod\Framework\Service;
use McVod\Framework\DataMapper;

class Test extends Service{
  public function index(){
    include(ROOT_DIR.'/McVod/Templates/test.html');
    exit;

  }
  public function test1(){
    $test = DataMapper::instance('McVod\\Model\\Test')->all();
    $this->_response->setBody($test->toArray());
  }
}
