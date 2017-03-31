<?php
/**
 * Article Controller
 * @author liangjian
 */

namespace app\admin\controller;
use app\admin\controller\Base;


class Article extends Base
{
    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * Article list
     */
    public function index(){
        $_res = model("Article")->page_all_article();
        $class = model("ArticleClassify")->all_classes();
        $replace = [
            'show' => [0 => '否', 1 => '是'],
            'class_id' => $class
        ];
        int_to_string($_res['_list'], $replace);
        $this->assign("_list", $_res['_list']);
        $this->assign("_page", $_res['_page']);
        return view("article/index");
    }

    /**
     * Add article
     */
    public function add(){
        if(request()->isPost()){
            $data = [];
            $data['title'] = input("post.title", "", "trim");
            $data['content'] = input("post.content", "", "htmlspecialchars");
            $data['class_id'] = input("post.class_id", 0, "intval");
            $data['sort'] = input("post.sort", 50, "trim");
            $data['show'] = input("post.show", 1, "intval");
            $validate = \think\Loader::validate('Article');
            if($validate->scene('add')->check($data)){
                $_res = model("Article")->save($data);
                if($_res){
                    return $this->success("文章添加成功！", url("article/index"));
                }else{
                    return $this->error("文章添加失败！");
                }
            }else{
                return $this->error($validate->getError());
            }
        }else{
            $class = model("ArticleClassify")->all_classes();
            $this->assign("_class", $class);
            return view("article/add");
        }
    }

    /**
     * Edit article
     */
    public function edit(){
        if(request()->isPost()){
            $data = [];
            $data['id'] = input("post.id", 0, "intval");
            $data['title'] = input("post.title", "", "trim");
            $data['content'] = input("post.content", "", "htmlspecialchars");
            $data['class_id'] = input("post.class_id", 0, "intval");
            $data['sort'] = input("post.sort", 50, "trim");
            $data['show'] = input("post.show", 1, "intval");
            $validate = \think\Loader::validate('Article');
            if($validate->scene('edit')->check($data)){
                $_res = model("Article")->update($data);
                if($_res){
                    return $this->success("文章修改成功！", url("article/index"));
                }else{
                    return $this->error("文章修改失败！");
                }
            }else{
                return $this->error($validate->getError());
            }
        }else{
            $id = input("id", 0, "intval");
            if($id){
                $info = model("Article")->article_by_id($id);
                if($info){
                    $class = model("ArticleClassify")->all_classes();
                    $this->assign("_class", $class);
                    $this->assign("_info", $info);
                    return view("article/edit");
                }else{
                    return $this->redirect(url("article/index"));
                }
            }else{
                return $this->redirect(url("article/index"));
            }
        }
    }

    /**
     * Ajax delete article
     */
    public function ajax_delete_article(){
        if(request()->isPost()){
            $id = input("post.id", 0, "intval");
            if($id){
                $map = ['id' => $id];
                if(model("Article")->where($map)->delete()){
                    return $this->success('文章删除成功！');
                } else {
                    return $this->error('文章删除失败！');
                }
            }else{
                return $this->error("系统错误，非法访问！");
            }
        }else{
            return $this->error("系统错误，非法访问！");
        }
    }

    /**
     * Article class
     */
    public function classify(){
        $_res = model("ArticleClassify")->page_all_classes();
        $replace = ['show' => [0 => '否', 1 => '是']];
        int_to_string($_res['_list'], $replace);
        $this->assign("_list", $_res['_list']);
        $this->assign("_page", $_res['_page']);
        return view("article/classes");
    }

    /**
     * Ajax add article class
     */
    public function ajax_add_class(){
        if(request()->isPost()){
            $data = [];
            $data['name'] = input("post.name", "", "trim");
            $data['sort'] = input("post.sort", 50, "trim");
            $data['desc'] = input("post.desc", "", "trim");
            $data['show'] = input("post.show", 1, "intval");
            $validate = \think\Loader::validate('ArticleClassify');
            if($validate->scene('add')->check($data)){
                $_res = model("ArticleClassify")->save($data);
                if($_res){
                    return $this->success("文章分类添加成功！");
                }else{
                    return $this->error("文章分类添加失败！");
                }
            }else{
                return $this->error($validate->getError());
            }
        }else{
            return $this->error("系统错误，非法访问！");
        }
    }

    /**
     * Ajax edit article class
     */
    public function ajax_edit_class(){
        if(request()->isPost()){
            $data = [];
            $data['id']   = input("post.id", 0, "intval");
            $data['name'] = input("post.name", "", "trim");
            $data['sort'] = input("post.sort", 50, "trim");
            $data['desc'] = input("post.desc", "", "trim");
            $data['show'] = input("post.show", 1, "intval");
            $validate = \think\Loader::validate('ArticleClassify');
            if($validate->scene('edit')->check($data)){
                $_res = model("ArticleClassify")->update($data);
                if($_res){
                    return $this->success("文章分类修改成功！");
                }else{
                    return $this->error("文章分类修改失败！");
                }
            }else{
                return $this->error($validate->getError());
            }
        }else{
            return $this->error("系统错误，非法访问！");
        }
    }

    /**
     * Ajax delete article class
     */
    public function ajax_delete_class(){
        if(request()->isPost()){
            $id = input("post.id", 0, "intval");
            if($id){
                $count = model("Article")->class_article_count($id);
                if($count > 0){
                    return $this->error("分类下存在文章，无法删除！");
                }else{
                    $map = ['id' => $id];
                    if(model("ArticleClassify")->where($map)->delete()){
                        return $this->success('文章分类删除成功！');
                    } else {
                        return $this->error('文章分类删除失败！');
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