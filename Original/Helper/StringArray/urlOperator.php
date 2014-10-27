<?php namespace Original\Helper\StringArray;
/**
* 收藏url相关操作的文件
*
* @author      张啟明 <1095841676@qq.com>
* @date 2014-10-27 下午9:41:29
* @copyright  张啟明
*/ 
class URLOpertor{
	/**
	 * 根据传过来的数组生成Request的参数
	 *
	 * @date: 2014-8-28 下午5:04:59
	 * @author: 张啟明<1095841676@qq.com>
	 * @param array $data 键值对数组
	 * @param array $ignoreCode 不URL编码的键值对，默认空
	 * @return:
	 */
	function encodeRequest($data, $ignoreCode = array()) {
		foreach($data as $k=>$v){
			if (!in_array($k, $ignoreCode)) {
				$v = urlencode($v);
			}
			$dataArr[] = "$k=$v";
		}
		$urlParameter = implode('&', $dataArr);
		return $urlParameter;
	}
}
