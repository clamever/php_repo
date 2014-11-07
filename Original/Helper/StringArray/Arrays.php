<?php	namespace Original\Helper\StringArray;
class Arrays {
	/**
	* 用来返回数组的子层，例如array("a" => array("b" => "c"));返回除去$key所指定的外层（如"a"），那么子层即返回为array("b" => "c")
	*
	* @date: 2014-11-7 上午11:05:02
	* @param array $array 待处理的数组
	* @param string $key 待剥去的外层，可以用.分开多个外层（例如"a.b"，即剥掉a和b两个外层）
	* @author: 张啟明<1095841676@qq.com>
	* @return: array $array 返回剥掉外层后的数组
	*/
	function array_fetch($array, $key)
	{
		foreach (explode('.', $key) as $segment)
		{
			$results = array();
	
			foreach ($array as $value)
			{
				$value = (array) $value;
	
				$results[] = $value[$segment];
			}
	
			$array = array_values($results);
		}
	
		return array_values($results);
	}
}