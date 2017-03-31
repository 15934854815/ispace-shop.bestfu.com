<?php
/**
 * Authorization Controller
 * @author liangjian
 */

namespace app\admin\controller;
use app\admin\controller\Base;

class Authorization extends Base
{
    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * Admin List
     */
    public function admins(){
        $model = model("User");
        $_res = $model->page_all_admins();
        $replace = [
            'status' => [0 => '正常', 1 => '禁用'],
        ];
        int_to_string($_res['_list'], $replace);
        $this->assign("_list", $_res['_list']);
        $this->assign("_page", $_res['_page']);
        return view('authorization/admins');
    }

    /**
     * Ajax Add Admin
     */
    public function ajax_add_admin(){
        if(request()->isPost()){
            $data = [];
            $data['username'] = input("post.username", "", "trim");
            $data['realname'] = input("post.realname", "", "trim");
            $data['password'] = input("post.password", "", "trim");
            $data['repassword'] = input("post.repassword", "", "trim");
            $data['status']   = input("post.status", 0, "intval");
            $validate = \think\Loader::validate('Admin');
            if($validate->scene('add_admin')->check($data)){
                $model = model("User");
                unset($data['repassword']);
                $_res = $model->save($data);
                if($_res){
                    return $this->success("管理员添加成功！");
                }else{
                    return $this->error("管理员添加失败！");
                }
            }else{
                return $this->error($validate->getError());
            }
        }else{
            return $this->error("系统错误，非法访问！");
        }
    }

    /**
     * Ajax Modify Admin
     */
    public function ajax_edit_admin(){
        if(request()->isPost()){
            $data = [];
            $data['id']         = input("post.id", 0, "intval");
            $data['username']   = input("post.username", "", "trim");
            $data['realname']   = input("post.realname", "", "trim");
            $data['pwd']        = input("post.password", "", "trim");
            $data['repwd']      = input("post.repassword", "", "trim");
            $data['status']     = input("post.status", 0, "intval");
            $validate = \think\Loader::validate('Admin');
            if($validate->scene('edit_admin')->check($data)){
                if(empty($data['pwd']) !== true){
                    $data['password'] = $data['pwd'];
                }
                unset($data['pwd']);
                unset($data['repwd']);
                $model = model("User");
                $_res = $model->update($data);
                if($_res !== false){
                    return $this->success("管理员修改成功！");
                }else{
                    return $this->error("管理员修改失败！");
                }
            }else{
                return $this->error($validate->getError());
            }
        }else{
            return $this->error("系统错误，非法访问！");
        }
    }

    /**
     * Ajax Delete Admin
     */
    public function ajax_delete_admin(){
        if(request()->isPost()){
            $id = input("post.id", 0, "intval");
            if($id && $id != config("user_administrator")){
                $model = model("User");
                $map = ['id' => $id];
                if($model->where($map)->delete()){
                    return $this->success('管理员删除成功！');
                } else {
                    return $this->error('管理员删除失败！');
                }
            }else{
                return $this->error("系统错误，非法访问！");
            }
        }else{
            return $this->error("系统错误，非法访问！");
        }
    }
}