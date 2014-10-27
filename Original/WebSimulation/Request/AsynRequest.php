<?php namespace Original\WebSimulation\Request;
/**
* 用来模拟异步请求的方法集
*
* @author      张啟明 <1095841676@qq.com>
* @date 2014-10-27 下午9:52:10
* @copyright  张啟明
*/ 
class AsynRequest{
	/**
	* 用来模拟异步的get请求
	* @param string 需要异步请求的url
	* @param Original\Helper\StringArray\URLOpertor->encodeRequest() $data 经过编码规范后的get请求参数
	* @return return_type
	* @author 张啟明
	* @date 2014-10-27下午9:53:53
	*/ 
	function asynDoGetRequest($url, $data) {
		$urlArr = parse_url($url);
		$fp = fsockopen($urlArr['host'], 80, $errno, $errstr, 5);
		if (!$fp)	{
			echo "$errstr ($errno)<br />/n";
		}
		$out = "GET {$urlArr['path']}?{$data} HTTP/1.1\r\n";
		$out .= "Host: {$urlArr['host']}\r\n";
		$out .= "Connection: Close\r\n\r\n";
		fwrite($fp, $out);
		// 	while (!feof($fp)) {
		// 		echo fread($fp, 128);
		// 	}
		fclose($fp);
	}
}