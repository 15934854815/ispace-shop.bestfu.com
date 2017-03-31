<?php
/**
 * Goods Controller
 * @author liangjian
 */

namespace app\admin\controller;
use app\admin\controller\Base;


class Goods extends Base
{
    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * Goods list
     * 商品列表
     */
    public function index(){
        $_res = model("Goods")->page_all_goods();
        dump($_res);
    }

    /**
     * Goods Classes
     * 商品分类列表
     */
    public function classify($id=0){
        $_res = model("Classify")->page_all_classes($id);
        $_info =  model("Classify")->class_by_id($id);
        $replace = [
            'hot' => [0 => '否', 1 => '是'],
            'show' => [0 => '否', 1 => '是'],
        ];
        int_to_string($_res['_list'], $replace);
        $this->assign("_list", $_res['_list']);
        $this->assign("_page", $_res['_page']);
        $this->assign("_info", $_info);
        return view("goods/classes");
    }

    /**
     * Ajax add goods class
     */
    public function ajax_add_class(){
        if(request()->isPost()){
            $data = [];
            $data['name']   = input("post.name", "", "trim");
            $data['mobile_name'] = input("post.mobile_name", "", "trim");
            $data['sort']   = input("post.sort", 50, "trim");
            $data['parent_id']  = input("post.parent_id", 0, "intval");
            $data['hot']    = input("post.hot", 0, "intval");
            $data['show']   = input("post.show", 1, "intval");
            $validate = \think\Loader::validate('Classify');
            if($validate->scene('add')->check($data)){
                $model = model("Classify");
                $_res = $model->save($data);
                if($_res){
                    return $this->success("商品分类添加成功！");
                }else{
                    return $this->error("商品分类添加失败！");
                }
            }else{
                return $this->error($validate->getError());
            }
        }else{
            return $this->error("系统错误，非法访问！");
        }
    }

    /**
     * Ajax edit goods class
     */
    public function ajax_edit_class(){
        if(request()->isPost()){
            $data = [];
            $data['id']     = input("post.id", 0, "intval");
            $data['name']   = input("post.name", "", "trim");
            $data['mobile_name'] = input("post.mobile_name", "", "trim");
            $data['sort']   = input("post.sort", 50, "trim");
            $data['hot']    = input("post.hot", 0, "intval");
            $data['show']   = input("post.show", 1, "intval");
            $validate = \think\Loader::validate('Classify');
            if($validate->scene('edit')->check($data)){
                $model = model("Classify");
                $_res = $model->update($data);
                if($_res){
                    return $this->success("商品分类修改成功！");
                }else{
                    return $this->error("商品分类修改失败！");
                }
            }else{
                return $this->error($validate->getError());
            }
        }else{
            return $this->error("系统错误，非法访问！");
        }
    }

    /**
     * Ajax delete goods class
     */
    public function ajax_delete_class(){
        if(request()->isPost()){
            $id = input("post.id", 0, "intval");
            if($id){
                $model = model("Classify");
                $child = $model->class_children_count($id);
                if($child > 0){
                    return $this->error("存在下属商品分类，无法删除！");
                }else{
                    $map = ['id' => $id];
                    if($model->where($map)->delete()){
                        return $this->success('商品分类删除成功！');
                    } else {
                        return $this->error('商品分类删除失败！');
                    }
                }
            }else{
                return $this->error("系统错误，非法访问！");
            }
        }else{
            return $this->error("系统错误，非法访问！");
        }
    }
}