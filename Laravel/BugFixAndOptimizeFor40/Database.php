<?php	namespace Laravel\BugFixAndOptimizeFor40;
class Database {
	/**
	 * 修复insertGetId不能自动更新时间戳的bug,将此方法加在
	 * Illuminate\Database\Eloquent\Model类中，通过在__call方法中
	 * 加上$parameters = $this->_bugFix($method, $parameters);修复
	 * @date: 2014-11-14 下午2:33:46
	 * @author: 张啟明<1095841676@qq.com>
	 * @return:
	 */
	private function _bugFixInsertGetId($method, $parameters) {
		if($method == "insertGetId" && $this->timestamps != false) {
			$datetime = date("Y-m-d h:i:s");
			$parameters = &$parameters;
			$parameters[0]['created_at'] = $datetime;
			$parameters[0]['updated_at'] = $datetime;
				
		}
		return $parameters;
	}
}