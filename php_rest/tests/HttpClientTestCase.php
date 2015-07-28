<?php

use McVod\Framework\HttpClient;

class HttpClientTestCase extends PHPUnit_Framework_TestCase{

  public function testGET(){
    HttpClient::instance()->decode_json = false;
    $result = HttpClient::instance()->get('http://www.baidu.com/');
    $this->assertNotEmpty($result);
  }

}
