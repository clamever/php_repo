<?php

namespace  McVod\Framework;
use McVod\Framework\Config;
use Spot\Mapper as SpotMapper;
use Spot\Config as SpotConfig;
use Spot\Locator as SpotLocator;
class DataMapper{
  private static $_instance = NULL;
  protected $_dataMapper = array();
  private static $_entityName = NULL;

  private function __construct(){
  }

  public static function instance($entityName){
    self::$_entityName = $entityName;
    if(! self::$_instance instanceof self){
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  public function __call($method,$args){
    $mapper = $this->_manager();
    $mapper->config()->setConnection('mysql_slave');
    $writeMethod = array(
      'insert',
      'update',
      'delete',
      'save',
      'create',
      'upsert',
    );
    if(in_array($method,$writeMethod)){
      $mapper->config()->setConnection('mysql_master');
    }
    return call_user_func_array(array($mapper,$method),$args);
  }
  private function _mapper(){
    $dbConfig = new SpotConfig();
    $dbConfig->addConnection('mysql_master',Config::get('db/mysql/master'));
    $dbConfig->addConnection('mysql_slave' ,Config::get('db/mysql/slave'));
    $locator = new SpotLocator($dbConfig);
    return new SpotMapper($locator,self::$_entityName);
  }

  private function _manager(){
    if(!isset($this->_dataMapper[self::$_entityName])){
      $this->_dataMapper[self::$_entityName] = $this->_mapper();
    }
    return $this->_dataMapper[self::$_entityName];
  }
}
