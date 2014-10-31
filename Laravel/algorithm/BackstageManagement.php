<?php namespace Laravel\algorithm;
/**
* 用于集成后台管理系统用到的一些算法 BackstageManagement.php
*
* @date: 2014-10-31 上午10:55:54
* @author: 张啟明<1095841676@qq.com>
* @version: 1.0
* @copyright: 2014 yasin.com.cn
*/
class BackstageManegement {
	/**
	 * 用与快速排序的数据库模型操作方法，以ShopInfo表为例，原理为先拿出，后插入，拿出时将后续范围的值减1，插入时将后续范围的值加1（范围限定为50）
	 *
	 * @date: 2014-10-17 下午1:44:33
	 * @author: 张啟明<1095841676@qq.com>
	 * @param int $orderNum 顺序号
	 * @param mixed $shopInfoId 目标id号()
	 * @param int $cityId 传入的附加查询
	 * @return: bool $success true成功 false失败
	 */
	public function quickSorting($orderNum, $shopInfoId, $cityId) {
		$shopInfo = ShopInfo::find($shopInfoId);
		$shopRank = $shopInfo->shop_rank;
		if($shopRank != $orderNum){
			//当原位置在50以内，从原位置取出并将后续目标的排序值减1
			if($shopRank <= 50 && $shopRank !=0){
				$shopInfos = ShopInfo::whereRaw('city_id = ? and shop_rank > ? and shop_rank <= ?', array($cityId, $shopRank, 50))->get();
				foreach ($shopInfos as $v){
					$v->decrement('shop_rank');
				}
			}
			$shop = ShopInfo::where('shop_rank', '=', $orderNum)->first();
			if($orderNum <= 50 && $orderNum !=0 && $shop){
				//插入排序中，将被插序值以及以后的值都加1
				$shopInfos = Shop_info::whereRaw('city_id = ? and shop_rank >= ? and shop_rank <= ?', array($cityId, $orderNum, 50))->get();
				foreach ($shopInfos as $v){
					$v->increment('shop_rank');
				}
			}
		}
		$shopInfo->shop_rank = $orderNum;
		$res = $shopInfo->save();
		return $res;
	}
}