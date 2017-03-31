<?php
/**
 * Member Model
 * @author liangjian
 */

namespace app\admin\model;
use think\Model;

class Member extends Model
{
    protected $pk = 'user_id';

    protected $table = 'bestfu_users';

    /**
     * 分页获取所有会员
     * @param $size 分页数量
     * @return array
     */
    public function page_all_members($size = null){
        $members = [];
        $page = null;
        $size = $size ? $size : config('page_size');
        $objs = $this->order('user_id DESC')->paginate($size);
        if($objs){
            foreach($objs as $obj){
                $members[] = $obj->getData();
            }
            $page = $objs->render();
        }
        return ['_list'=>$members, '_page'=>$page];
    }

    /**
     * 根据ID获取会员信息
     */
    public function member_by_id($id, $field='*'){
        $member = [];
        if($id){
            $map = ['user_id' => $id];
            $obj = $this->field($field)->where($map)->find();
            if($obj){
                $member = $obj->getData();
            }
        }
        return $member;
    }
}