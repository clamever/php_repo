<?php
namespace McVod\Framework;

class Request{
  private $_request = NULL;
  private $_session = NULL;
  private $_cookie  = NULL;
  public function __construct(){
    $this->_request = array();
    $this->_request['domain']   = isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:'';
    $this->_request['uri']      = isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:'';
    $this->_request['method']   = isset($_SERVER['REQUEST_METHOD'])?$_SERVER['REQUEST_METHOD']:'';
    $this->_request['protocol'] = isset($_SERVER['SERVER_PROTOCOL'])?$_SERVER['SERVER_PROTOCOL']:'';
    $this->_request['ua']       = isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'';
    $this->_request['time']     = isset($_SERVER['REQUEST_TIME_FLOAT'])?$_SERVER['REQUEST_TIME_FLOAT']:'';
    $this->_request['ip']       = $this->IP();

    $this->_request['postData'] = new \stdClass();
    $this->_request['putData']  = new \stdClass();
    if(isset($_SERVER['HTTP_HOST'])){
      if (is_array($_POST) && !empty($_POST)) {
        foreach($_POST as $key=>$value){
          $this->_request['postData']->$key = escapeString($value);
        }
      }

      $_PUT = array();
      if ('PUT' == $_SERVER['REQUEST_METHOD']) {
        parse_str(file_get_contents('php://input'), $_PUT);
        foreach($_PUT as $key => $value){
          $this->_request['putData']->$key = escapeString($value);
        }
      }
      $this->_session = new Session();
      $this->_cookie  = new Cookie();
    }

  }

  public function __set($key,$value){
    $this->_request[$key] = $value;
  }

  public function __get($key){
    if (isset($this->_request[$key])) {
      return $this->_request[$key];
    }
    return NULL;
  }

  public function cookie(){
    return $this->_cookie;
  }

  public function session(){
    return $this->_session;
  }

  public function isAjax(){
    if ( isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) {
      return true;
    }
    return false;
  }

  public function isMobile()
  {
      // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
      if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])){
          return true;
      }
      // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
      if (isset ($_SERVER['HTTP_VIA'])){
          // 找不到为flase,否则为true
          return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
      }
      // 脑残法，判断手机发送的客户端标志,兼容性有待提高
      if (isset ($_SERVER['HTTP_USER_AGENT'])){
          $clientkeywords = array ('nokia',
              'sony',
              'ericsson',
              'mot',
              'samsung',
              'htc',
              'sgh',
              'lg',
              'sharp',
              'sie-',
              'philips',
              'panasonic',
              'alcatel',
              'lenovo',
              'iphone',
              'ipod',
              'blackberry',
              'meizu',
              'android',
              'netfront',
              'symbian',
              'ucweb',
              'windowsce',
              'palm',
              'operamini',
              'operamobi',
              'openwave',
              'nexusone',
              'cldc',
              'midp',
              'wap',
              'mobile'
              );
          // 从HTTP_USER_AGENT中查找手机浏览器的关键字
          if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))){
              return true;
          }
      }
      // 协议法，因为有可能不准确，放到最后判断
      if (isset ($_SERVER['HTTP_ACCEPT'])){
          // 如果只支持wml并且不支持html那一定是移动设备
          // 如果支持wml和html但是wml在html之前则是移动设备
          if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))){
              return true;
          }
      }
      return false;
  }

  private function IP(){
    if(!isset($_SERVER)){
      return '';
    }
    if(!empty($_SERVER["HTTP_CLIENT_IP"])){
      $cip = $_SERVER["HTTP_CLIENT_IP"];
    }
    elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
      $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    }
    elseif(!empty($_SERVER["REMOTE_ADDR"])){
      $cip = $_SERVER["REMOTE_ADDR"];
    }
    else{
      $cip = NULL;
    }
    return $cip;
  }
}

class Session{
  private $_session = NULL;

  public function __construct(){
    if (PHP_SESSION_ACTIVE == session_status()) {
      $this->_session = $_SESSION;
    }
  }

  public function __set($key,$value){
    if ($this->_session) {
      $value = escapeString($value);
      $this->_session[$key] = $value;
      $_SESSION[$key] = $value;
      return true;
    }
    return false;
  }

  public function __get($key){
    if ($this->_session && isset($this->_session[$key])) {
      $this->_session[$key] = escapeString($this->_session[$key]);
      return $this->_session[$key];
    }

    if (isset($_SESSION[$key])) {
      $this->_session[$key] = escapeString($_SESSION[$key]);
      return $this->_session[$Key];
    }
    return NULL;
  }

  public function del($key){
    if($this->_session && isset($_SESSION[$key])){
      unset($_SESSION[$key]);
      if (isset($this->_session[$key])) {
        unset($this->_session[$key]);
      }
    }
  }
}

class Cookie{
  private $_cookie = NULL;

  public function __construct(){
    $this->_cookie = $_COOKIE;
  }

  public function __set($key,$value){
    if ($this->_cookie) {
      $value = escapeString($value);
      $this->_cookie[$key] = $value;
      setcookie($key,$value);
      return true;
    }
    return false;
  }

  public function __get($key){
    if ($this->_cookie && isset($this->_cookie[$key])) {
      $this->_cookie[$key] = escapeString($this->_cookie[$key]);
      return $this->_cookie[$key];
    }

    if (isset($_COOKIE[$key])) {
      $this->_cookie[$key] = escapeString($_COOKIE[$key]);
      return $this->_cookie[$key];
    }
    return NULL;
  }

  public function del($key){
    if($this->_cookie && isset($_COOKIE[$key])){
      unset($_COOKIE[$key]);
      setcookie($key,'',time()-1);
      if (isset($this->_cookie[$key])) {
        unset($this->_cookie[$key]);
      }
    }
  }
}


function escapeString($str){
  return !get_magic_quotes_gpc()?addslashes(htmlspecialchars($str)):htmlspecialchars($str);
}

function addSlashesExtended(&$arr_r)
{
    if(is_array($arr_r)){
        foreach ($arr_r as &$val){
          is_array($val) ? addSlashesExtended($val):$val=escapeString($val);
        }
        unset($val);
    }
    else{
      $arr_r=escapeString($arr_r);
    }
}
