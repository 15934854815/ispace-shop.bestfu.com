<?php
/**
 * Goods Classify Model
 * @author liangjian
 */

namespace app\admin\model;
use think\Model;

class Classify extends Model
{
    protected $table = 'bestfu_goods_classify';
    /**
     * 分页获取商品分类
     * @param $parent 父级id
     * @param $size 分页数量
     * @return array
     */
    public function page_all_classes($parent_id = 0, $size = null){
        $classes = [];
        $page = null;
        $map = ['parent_id' => $parent_id];
        $size = $size ? $size : config('page_size');
        $objs = $this->where($map)->order('sort ASC, id ASC')->paginate($size);
        if($objs){
            foreach($objs as $obj){
                $classes[] = $obj->getData();
            }
            $page = $objs->render();
        }
        return ['_list'=>$classes, '_page'=>$page];
    }

    /**
     * 根据id获取分类信息
     * @param $id 分类id
     * @return array
     */
    public function class_by_id($id){
        $class = [];
        if($id){
            $map = ['id' => $id];
            $obj = $this->where($map)->find();
            if($obj){
                $temp = $class = $obj->getData();
                $parent = $temp['name'];
                while (true){
                    if($temp['parent_id'] == 0){
                        break;
                    }else{
                        $m = ['id' => $temp['parent_id']];
                        $o = $this->where($m)->find();
                        if($o){
                            $temp = $o->getData();
                            $parent = "{$temp['name']}->{$parent}";
                        }else{
                            break;
                        }
                    }
                }
                $class['parent'] = $parent;
            }
        }
        return $class;
    }

    /**
     * 根据id获取其子分类数量
     * @param $id 区域id
     * @return integer
     */
    public function class_children_count($id = 0){
        $child = 0;
        if(is_numeric($id)){
            $map = ['parent_id' => $id];
            $child = $this->where($map)->count();
        }
        return $child;
    }
}