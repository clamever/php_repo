<?php
namespace McVod;

define('DS',DIRECTORY_SEPARATOR);
define('ROOT_DIR',dirname(dirname(__FILE__))).DS;
define('CONFIG_DIR',ROOT_DIR.DS.'McVod'.DS.'Config'.DS);
if(!isset($_SERVER['RUN_MODE']) || empty($_SERVER['RUN_MODE'])){
  $_SERVER['RUN_MODE'] = 'develop';
}
define('RUN_MODE',$_SERVER['RUN_MODE']);

use McVod\Framework\Request;
use McVod\Framework\Response;

class Application{
  private $_beforeMiddleware = array();
  private $_afterMiddleware  = array();
  private $_routes     = array();
  private $_request    = null;
  private $_response   = null;

  public function __construct(){
    $this->_request = new Request();
    $this->_response = new Response();
    if(strlen($this->_request->domain)>0){
      session_start();
    }
  }

  public function registerRoute(array $route){
    $this->_routes[] = $route;
  }

  public function registerRouteBatch(array $routes){
    foreach($routes  as $route){
      $this->_routes[] = $route;
    }
  }

  public function registerBeforeMiddleware($middlewareName){
    if(strlen($middlewareName)>0){
      $this->_beforeMiddleware[] = $middlewareName;
    }
  }

  public function registerAfterMiddleware($middlewareName){
    if(strlen($middlewareName)>0){
      $this->_afterMiddleware[] = $middlewareName;
    }
  }

  private function executeBeforeMiddleware($route){
    foreach($this->_beforeMiddleware as $middleware){
      if(isset($route['skipMiddleware']) && $route['skipMiddleware']==$middleware){
        continue;
      }
      $m = new $middleware($this->_request,$this->_response);
      $response = $m->execute();
      if($response instanceof Response){
        return $response->sendResponse();
      }
    }
  }

  public function run(){
    $route = $this->dispatch();
    if(empty($route)){
      $this->_response->setCode(404);
      return $this->_response->sendResponse();
    }
    $this->executeBeforeMiddleware($route);
    $serviceName = $route['callback'][0];
    $serviceMethod = $route['callback'][1];
    $service = new $serviceName($this->_request,$this->_response);
    $service->$serviceMethod();
    $this->executeAfterMiddleware($route);
    $service->sendResponse();
  }

  private function executeAfterMiddleware($route){
    foreach($this->_afterMiddleware as $middleware){
      if(isset($route['skipMiddleware']) && $route['skipMiddleware']==$middleware){
        continue;
      }
      $m = new $middleware($this->_request,$this->_response);
      $response = $m->execute();
      if($response instanceof Response){
        return $response->sendResponse();
      }
    }
  }

  private function dispatch(){
    $matchPattern = array();
    foreach ($this->_routes as $m) {
      $pma = preg_match($m['requestPattern'],$this->_request->uri,$matches);
      if($pma > 0 && $m['requestMethod'] == $this->_request->method) {
        unset($matches[0]);
        $parameters = array();
        foreach($m['parameters'] as $k => $v){
          $param = @$matches[$k][0];
          $parameters[$v] = $param;
          $this->_request->$v = escapeString($param);
        }
        $matchPattern['requestMethod']  = $m['requestMethod'];
        $matchPattern['requestPattern'] = $m['requestPattern'];
        $matchPattern['callback']       = $m['callback'];
        $matchPattern['skipMiddleware']     = $m['skipMiddleware'];
        break;
      }
    }
    return $matchPattern;
  }
}
