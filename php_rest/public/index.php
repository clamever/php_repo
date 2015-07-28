<?php
date_default_timezone_set('Asia/Shanghai');
require('../vendor/autoload.php');

use McVod\Application;
use McVod\Framework\Config;
use McVod\Framework\Response;

try{
  $app = new Application();
  $app->registerBeforeMiddleware('McVod\\Middleware\\Authentication');
  $app->registerRouteBatch(Config::get('route'));
  $app->run();
}catch(Exception $ex){
  $response = new Response(500);
  $response->setBody(array('error',$ex->getMessage()));
  $response->sendResponse();
}


function dump($val){
  echo '<pre>';
  var_dump($val);
  echo '</pre>';
}
