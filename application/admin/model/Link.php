<?php
/**
 * Links Model
 * @author liangjian
 */

namespace app\admin\model;
use think\Model;


class Link extends Model
{
    /**
     * 分页获取友情链接
     * @param $size 分页数量
     * @return array
     */
    public function page_all_links($size = null){
        $links = [];
        $page = null;
        $size = $size ? $size : config('page_size');
        $objs = $this->order('sort ASC, id DESC')->paginate($size);
        if($objs){
            foreach($objs as $obj){
                $links[] = $obj->getData();
            }
            $page = $objs->render();
        }
        return ['_list'=>$links, '_page'=>$page];
    }
}