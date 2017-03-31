<?php
/**
 * Order Goods Model
 * @author liangjian
 * @email liangjian@bestfu.com
 * @date 2017-03-13
 */

namespace app\admin\model;
use think\Model;

class OrderGoods extends Model
{
    /**
     * 根据订单ID获取商品列表
     * @param $order_id 订单ID
     * @return array
     */
    public function goods_by_order_id($order_id){
        $goods = [];
        $map = ['order_id' => $order_id];
        $objs = $this->where($map)->select();
        if($objs){
            foreach($objs as $obj){
                $goods[] = $obj->getData();
            }
        }
        return $goods;
    }
}