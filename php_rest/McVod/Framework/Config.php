<?php

namespace McVod\Framework;

class Config{
  private static $_instance;
  private $_config = NULL;
  private $_cache  = NULL;

  public static function get($k){
    if (empty($k)) {
      return NULL;
    }
    self::getInstance();
    if (isset(self::$_instance->_cache[$k]) && !empty(self::$_instance->_cache[$k])) {
      return self::$_instance->_cache[$k];
    }

    if (isset(self::$_instance->_config[$k])) {
      self::$_instance->_cache[$k] = self::$_instance->_config[$k];
      return self::$_instance->_config[$k];
    }

    $configKeys = explode('/',$k);
    $conf = array();
    if (!isset(self::$_instance->_config[$configKeys[0]])) {
      $conf = self::$_instance->loadConfigFromFile($configKeys[0]);
      unset($configKeys[0]);
    }else{
      $conf = self::$_instance->_config[$configKeys[0]];
      unset($configKeys[0]);
    }
    foreach($configKeys as $key){
      if (is_array($conf) && isset($conf[$key])){
        $conf = $conf[$key];
      }
    }
    if(is_array($conf)){
      $diffKey = array_diff_key($conf,self::$_instance->_config);
      if (!empty($diffKey)) {
        self::$_instance->_cache[$k] = $conf;
        return $conf;
      }
    }
    
    return $conf;
  }

  public static function set($k,$v){
    self::getInstance();
    self::$_instance->_config[$k] = $v;
  }

  private static function getInstance(){
      if (! self::$_instance instanceof self) {
        self::$_instance = new self();
      }
      return self::$_instance;
  }


  private function __construct(){
    $this->_config = array();
  }

  private function loadConfigFromFile($configFileName){
    $configFilePath = CONFIG_DIR.RUN_MODE.DS.$configFileName.'.php';
    if($configFileName == 'route'){
      $configFilePath = CONFIG_DIR.$configFileName.'.php';
    }
    if (file_exists($configFilePath)) {

      return include($configFilePath);
    }
    return NULL;
  }

}
