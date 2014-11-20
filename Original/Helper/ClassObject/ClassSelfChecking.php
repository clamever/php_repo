<?php	namespace Original\Helper\ClassObject;
/**
* 用来操作类和对象的助手方法类 ClassSelfChecking.php
*
* @date: 2014-11-20 下午2:00:35
* @author: 张啟明<1095841676@qq.com>
* @version: 1.0
* @copyright: 2014 yasin.com.cn
*/
class ClassSelfChecking {
	/**
	* 检测某个类的父类中是否有某个方法
	*
	* @date: 2014-11-20 下午2:00:31
	* @author: 张啟明<1095841676@qq.com>
	* @return:
	*/
	public function checkParentClassMethodIsExist($class, $method) {
		$parentClass = class_parents($class);
		foreach($parentClass as $parent){
			if(method_exists($parent,$method)){
				echo $parent;
			}
		}
	}
}
