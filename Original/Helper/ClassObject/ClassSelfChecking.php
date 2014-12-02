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
	
	/**
	* 检测加载到的类哪些是用户自定义
	*
	* @date: 2014-12-2 上午10:24:27
	* @author: 张啟明<1095841676@qq.com>
	* @return:
	*/
	public function checkUserDefinedClass() {
		foreach (get_declared_classes() as $class) {
			$reflectionClass = new \ReflectionClass($class);
			if(strstr($reflectionClass->getName(), '')) {
				echo $reflectionClass->getName().'<br>';
			}
		}
	}
	
	/**
	* 用于检测类中哪些方法是继承的，哪些方法时非继承的
	*
	* @date: 2014-12-2 上午11:21:26
	* @param string $className 需要检查的类名
	* @param string $option 
	* 		  	"ALL" 取得全部方法
	* 			"CHILD" 只取得子类的方法
	* @author: 张啟明<1095841676@qq.com>
	* @return: array $methods 包含子类方法的反射对象
	*/
	public function checkInheritMethods($className, $option) {
		$rc = new \ReflectionClass($className);
		$methods = array();
		foreach ($rc->getMethods() as $m) {
			try {
				if ($m->getPrototype()) {
					
					($option == "ALL")?($methods[$m->name] = $m):"";
				}
			} catch (\ReflectionException $e) {
				if($option == "ALL"){
					$methods[$m->name] = $m;
				}else{
					($m->class == $className) ? ($methods[$m->name] = $m) : "";
				}
			}
		}
		return $methods;
	}
}
