<?php
/**
 * Article Model
 * @author liangjian
 */


namespace app\admin\model;
use think\Model;

class Article extends Model
{
    protected $insert = ['addtime'];

    protected function setAddtimeAttr()
    {
        return request()->time();
    }

    /**
     * 分页获取文章
     * @param $size 分页数量
     * @return array
     */
    public function page_all_article($size = null){
        $articles = [];
        $page = null;
        $size = $size ? $size : config('page_size');
        $objs = $this->order('sort ASC,id DESC')->paginate($size);
        if($objs){
            foreach($objs as $obj){
                $articles[] = $obj->getData();
            }
            $page = $objs->render();
        }
        return ['_list'=>$articles, '_page'=>$page];
    }

    /**
     * 根据分类id获取其下属文章数量
     * @param $id 区域id
     * @return integer
     */
    public function class_article_count($id = 0){
        $count = 0;
        if(is_numeric($id)){
            $map = ['class_id' => $id];
            $count = $this->where($map)->count();
        }
        return $count;
    }

    /**
     * 根据ID获取文章信息
     */
    public function article_by_id($id, $field='*'){
        $article = [];
        if($id){
            $map = ['id' => $id];
            $obj = $this->field($field)->where($map)->find();
            if($obj){
                $article = $obj->getData();
            }
        }
        return $article;
    }
}