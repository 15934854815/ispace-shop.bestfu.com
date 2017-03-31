<?php
/**
 * Navigation Model
 * @author liangjian
 */

namespace app\admin\model;
use think\Model;

class Navigation extends Model
{
    /**
     * 分页获取自定义导航
     * @param $size 分页数量
     * @return array
     */
    public function page_all_navs($size = null){
        $navs = [];
        $page = null;
        $size = $size ? $size : config('page_size');
        $objs = $this->order('sort ASC, id DESC')->paginate($size);
        if($objs){
            foreach($objs as $obj){
                $navs[] = $obj->getData();
            }
            $page = $objs->render();
        }
        return ['_list'=>$navs, '_page'=>$page];
    }
}