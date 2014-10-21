<?php
/**
* 用于datatables分页的模型类，让其他数据表模型继承此模型则可使用此类中方法。
*
* @date: 2014-10-15 下午4:42:07
* @author: 张啟明<1095841676@qq.com>
* @return:
*/
class DatatablesModel extends Eloquent {
	protected  $_where = null;
	protected  $_order = null;
	
	/**
	* 用来设置分页查询where条件，设置$_where字段
	*
	* @date: 2014-10-15 下午4:41:40
	* @author: 张啟明<1095841676@qq.com>
	* @param array $where key：字段名+操作符（如"status in"） value:字段值（如"20,30,40"）
	* @param string $type 连接符
	* @return: DatatablesModel
	*/
	public function setWhere($where = null, $type = 'and') {
		if (empty($where) || !is_array($where))
			return $this;
	
		$data = array();
		$data['bind'] = array();
		$searchString = '';
	
		foreach ($where as $search => $value) {
			$isIn = strstr($search, ' in');
				
			if($isIn){
				$searchString .= ' ' . trim($search) . " ($value) " . $type;
			}else {
				$searchString .= ' ' . trim($search) . ' ? ' . $type;
				$data['bind'][] = trim($value);
			}
		}
	
		$data['param'] = substr_replace(trim($searchString, ' '), '', -4); // 去掉最后的 " and"
		$this->_where = $data;
		return $this;
	}
	
	/**
	* 用来设置分页查询排序条件，设置$this->_order字段;
	*
	* @date: 2014-10-15 下午4:47:12
	* @author: 张啟明<1095841676@qq.com>
	* @param array $order key:字段名 value:排序方式（desc, asc）
	* @return: DatatablesModel
	*/
	public function setOrder($order = null) {
		if (empty($order) || !is_array($order))
			return $this;
	
		$orderString = '';
	
		foreach ($order as $field => $type) {
			$orderString .= $field . ' ' . $type . ',';
		}
		$this->_order = trim($orderString . $this->_order, ',');
	
		return $this;
	}
	
	/**
	* 利用$_where和$_order进行查询
	*
	* @date: 2014-10-15 下午4:49:02
	* @author: 张啟明<1095841676@qq.com>
	* @param: int $page 页号
	* @param: int $limit 每页条数
	* @return: Illuminate\Database\Eloquent\Model 
	*/
	public function getListByPage($page = 0, $limit = 3) {
		$obj = $this;
		// 注册搜索条件
		if (!empty($this->_where)) {
			$obj = $this->whereRaw($this->_where['param'], $this->_where['bind']);
		}
		 
		return $obj->offset($page)->limit($limit)
		->orderByRaw($this->_order)
		->get();
	}
	
	/**
	* 获取查询条件
	*
	* @date: 2014-9-1 下午1:29:23
	* @author: 张啟明<1095841676@qq.com>
	* @return:
	*/
	public function getWhere() {
		return $this->_where;
	}
	
	/**
	* 获取排序条件
	*
	* @date: 2014-9-1 下午1:30:00
	* @author: 张啟明<1095841676@qq.com>
	* @return:
	*/
	public function getOrder() {
		return $this->_order;
	}
	
	/**
	* 用来返回后台页面中的操作按钮
	* @param array $data(key:动作名称 value:动作的url)
	* @date: 2014-9-29 下午5:34:56
	* @author: 张啟明<1095841676@qq.com>
	* @return: string 按钮的html代码
	*/
	public function getButton($data) {
		$rs = null;
		foreach ($data as $k => $v){
			if($k != "删除"){
				$rs .= <<<HTML
				<a target="_blank" class="btn btn-mini btn-info" href='$v'>
				$k
				</a>
HTML;
			}else{
				$rs .= <<<HTML
				<a class="btn btn-mini btn-danger" onclick="del('$v');">
				$k
				</a>			
HTML;
			}
		}
		return $rs;
	}
	
	/**
	 * 用来计算查询出的总条数
	 *
	 * @date: 2014-10-21 下午2:59:22
	 * @author: 张啟明<1095841676@qq.com>
	 * @return:
	 */
	public function getCount() {
		$obj = $this;
			
		if (!empty($this->_where)) {
			$obj = $this->whereRaw($this->_where['param'], $this->_where['bind']);
		}
		return $obj->count();
	}
}