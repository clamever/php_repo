<?php

namespace McVod\Model;

use McVod\Framework\Entity;
class Test extends Entity{
  protected static $table = 'AccompanyLanguage';
  public static function fields(){
    return array(
      'id'     => array('type'=>'integer','primary'=>true,'autoincrement'=>true),
      'name'   => array('type'=>'string','required'=>true),
      'seq'    => array('type'=>'integer','default'=>0),
    );
  }
}
