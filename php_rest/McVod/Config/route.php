<?php
return array(
  // 'test' => array(
  //   'requestPattern' => '/\/test/',
  //   'requestMethod'  => 'GET',
  //   'skipMiddleware' => array(),
  //   'callback'       => array('McVod\\Service\\Test','test1'),
  //   'parameters'     => array(
  //
  //   )
  // ),
  'test1' => array(
    'requestPattern' => '/\/test1/',
    'requestMethod'  => 'GET',
    'skipMiddleware' => array(),
    'callback'       => array('McVod\\Service\\Main','index'),
    'parameters'     => array(

    )
  ),
  'home' => array(
    'requestPattern' => '/\//',
    'requestMethod'  => 'GET',
    'skipMiddleware' => array(),
    'callback'       => array('McVod\\Service\\Test','index'),
    'parameters'     => array(

    )
  ),

);
