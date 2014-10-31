<?php	namespace Original\Helper\StringArray;
/**
* 用于格式化某些特殊的字符串格式 Formatter.php
*
* @date: 2014-10-28 下午2:02:23
* @author: 张啟明<1095841676@qq.com>
* @version: 1.0
* @copyright: 2014 yasin.com.cn
*/
class Formatter {
	/**
	* 用来将邮件地址格式化为header头中的标准地址格式，例如a<b@c.com>
	*
	* @date: 2014-10-28 下午2:07:53
	* @param $hostName 主机名（上面的a）
	* @param $address 邮件地址名（上面的b@c.com）
	* @author: 张啟明<1095841676@qq.com>
	* @return: 
	*/
	public function formatSendEmailAddress($hostName,$address) {
		$formattedAddress = $hostName . "<" . $address . ">";
		return $formattedAddress;
	}
	/**
	* 用来将下划线分割式字符串改为驼峰式字符串
	*
	* @date: 2014-10-31 下午12:10:29
	* @author: 张啟明<1095841676@qq.com>
	* @param $value 待转换的下划线分割式字符串
	* @return:$value
	*/
	public function camer($value) {
		$value = ucwords(str_replace(array('-', '_'), ' ', $value));
		$value = str_replace(' ', '', $value);
		$value = lcfirst($value);
		return $value;
	}
}
