<?php
/**
 * Region Model
 * @author liangjian
 */

namespace app\admin\model;
use think\Model;

class Region extends Model
{
    /**
     * 分页获取区域列表
     * @param $parent 父级id
     * @param $size 分页数量
     * @return array
     */
    public function page_all_regions($parent = 0, $size = null){
        $regions = [];
        $page = null;
        $map = ['parent_id' => $parent];
        $size = $size ? $size : config('page_size');
        $objs = $this->where($map)->order('id ASC')->paginate($size);
        if($objs){
            foreach($objs as $obj){
                $regions[] = $obj->getData();
            }
            $page = $objs->render();
        }
        return ['_list'=>$regions, '_page'=>$page];
    }

    /**
     * 获取区域列表
     * @param $parent 父级id
     * @return array
     */
    public function all_regions($parent = 0){
        $regions = [];
        $map = ['parent_id' => $parent];
        $objs = $this->where($map)->order('id ASC')->select();
        if($objs){
            foreach($objs as $obj){
                $regions[] = $obj->getData();
            }
        }
        return $regions;
    }

    /**
     * 以id为索引获取地区列表
     */
    public function all_region_lists(){
        return $this->column('name', 'id');
    }

    /**
     * 根据id获取区域信息
     * @param $id 区域id
     * @return array
     */
    public function region_by_id($id){
        $region = [];
        if($id){
            $map = ['id' => $id];
            $obj = $this->where($map)->find();
            if($obj){
                $temp = $region = $obj->getData();
                $parent = $temp['name'];
                while (true){
                    if($temp['level'] == 1){
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
                $region['parent'] = $parent;
            }
        }
        return $region;
    }

    /**
     * 根据id获取其子区域数量
     * @param $id 区域id
     * @return integer
     */
    public function region_children_count($id = 0){
        $child = 0;
        if(is_numeric($id)){
            $map = ['parent_id' => $id];
            $child = $this->where($map)->count();
        }
        return $child;
    }

    /**
     * 根据id获取其子区域列表
     * @param $id 区域id
     * @return integer
     */
    public function region_children($id = 0){
        $children = [];
        if(is_numeric($id)){
            $map = ['parent_id' => $id];
            $children = $this->where($map)->column('name','id');
        }
        return $children;
    }
}