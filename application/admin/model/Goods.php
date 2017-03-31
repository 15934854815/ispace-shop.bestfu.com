<?php
/**
 * Goods Model
 * @author liangjian
 * @email liangjian@bestfu.com
 */


namespace app\admin\model;
use think\Model;

class Goods extends Model
{
    /**
     * 分页获取商品列表
     * @param $size 分页数量
     * @return array
     */
    public function page_all_goods($size = null){
        $goods = [];
        $page = null;
        $size = $size ? $size : config('page_size');
        $objs = $this->order('sort ASC, id DESC')->paginate($size);
        if($objs){
            foreach($objs as $obj){
                $goods[] = $obj->getData();
            }
            $page = $objs->render();
        }
        return ['_list'=>$goods, '_page'=>$page];
    }
}