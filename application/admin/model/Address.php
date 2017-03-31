<?php
/**
 * Member Address Model
 * @author liangjian
 */

namespace app\admin\model;
use think\Model;

class Address extends Model
{
    protected $table = 'bestfu_user_address';

    /**
     * 分页获取会员收获地址
     * @param $user_id 会员ID
     * @param $size 分页数量
     * @return array
     */
    public function page_all_address($user_id, $size = null){
        $address = [];
        $page = null;
        $map = ['user_id' => $user_id];
        $size = $size ? $size : config('page_size');
        $objs = $this->where($map)->order('id ASC')->paginate($size);
        if($objs){
            foreach($objs as $obj){
                $address[] = $obj->getData();
            }
            $page = $objs->render();
        }
        return ['_list'=>$address, '_page'=>$page];
    }

    /**
     * 根据ID获取收货地址信息
     */
    public function address_by_id($id, $field='*'){
        $address = [];
        if($id){
            $map = ['id' => $id];
            $obj = $this->field($field)->where($map)->find();
            if($obj){
                $address = $obj->getData();
            }
        }
        return $address;
    }
}