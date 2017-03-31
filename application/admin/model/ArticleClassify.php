<?php
/**
 * Article Classify Model
 * @author liangjian
 */

namespace app\admin\model;
use think\Model;

class ArticleClassify extends Model
{
    protected $insert = ['addtime'];

    protected function setAddtimeAttr()
    {
        return request()->time();
    }

    /**
     * 分页获取文章分类
     * @param $size 分页数量
     * @return array
     */
    public function page_all_classes($size = null){
        $classes = [];
        $page = null;
        $size = $size ? $size : config('page_size');
        $objs = $this->order('sort ASC,id ASC')->paginate($size);
        if($objs){
            foreach($objs as $obj){
                $classes[] = $obj->getData();
            }
            $page = $objs->render();
        }
        return ['_list'=>$classes, '_page'=>$page];
    }

    /**
     * 获取文章分类
     * @return array
     */
    public function all_classes(){
        return $this->column('name','id');
    }

    /**
     * 根据ID获取分类信息
     */
    public function class_by_id($id, $field='*'){
        $class = [];
        if($id){
            $map = ['id' => $id];
            $obj = $this->field($field)->where($map)->find();
            if($obj){
                $class = $obj->getData();
            }
        }
        return $class;
    }
}