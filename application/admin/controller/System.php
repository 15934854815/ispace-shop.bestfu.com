<?php
/**
 * System Controller
 * @author liangjian
 */

namespace app\admin\controller;
use app\admin\controller\Base;
use think\Db;

class System extends Base
{
    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * Store Configuration
     */
    public function index(){
        if(request()->isPost()){
            $config = input("post.");
            if($config && is_array($config)){
                Db::startTrans();
                try{
                    $model = model("Config");
                    $params = [];
                    $keys = array_keys($config);
                    $map = ['name' => ['IN', $keys]];
                    foreach ($config as $key => $value) {
                        $params[] = [
                            'name' => $key,
                            'value'	=> $value
                        ];
                    }
                    $model->where($map)->delete();
                    $model->saveAll($params);
                    // 提交事务
                    Db::commit();
                    $resp = true;
                } catch (\Exception $e) {
                    // 回滚事务
                    Db::rollback();
                    $resp = false;
                }
                if($resp){
                    return $this->success('商城设置成功！');
                }else{
                    return $this->error("商城设置失败！");
                }
            }else{
                return $this->error("参数错误，保存失败！");
            }
        }else{
            $model = model("Config");
            $cfgs = $model->configs();
            $this->assign('cfgs', $cfgs);
            return view('system/index');
        }
    }

    /**
     * Friendly Links
     */
    public function link(){
        $model = model("Link");
        $_res = $model->page_all_links();
        $replace = [
            'show' => [0 => '否', 1 => '是'],
            'target' => [0 => '否', 1 => '是'],
        ];
        int_to_string($_res['_list'], $replace);
        $this->assign("_list", $_res['_list']);
        $this->assign("_page", $_res['_page']);
        return view('system/links');
    }

    /**
     * Ajax Add Friendly Link
     */
    public function ajax_add_link(){
        if(request()->isPost()){
            $data = [];
            $data['name']   = input("post.name", "", "trim");
            $data['url']    = input("post.url", "", "trim");
            $data['sort']   = input("post.sort", 50, "trim");
            $data['show']   = input("post.show", 1, "intval");
            $data['target'] = input("post.target", 0, "intval");
            $validate = \think\Loader::validate('Link');
            if($validate->scene('add_link')->check($data)){
                $model = model("Link");
                $_res = $model->save($data);
                if($_res){
                    return $this->success("友情链接添加成功！");
                }else{
                    return $this->error("友情链接添加失败！");
                }
            }else{
                return $this->error($validate->getError());
            }
        }else{
            return $this->error("系统错误，非法访问！");
        }
    }

    /**
     * Ajax Remove Friendly Link
     */
    public function ajax_delete_link(){
        if(request()->isPost()){
            $id = input("post.id", 0, "intval");
            if($id){
                $model = model("Link");
                $map = ['id' => $id];
                if($model->where($map)->delete()){
                    return $this->success('删除成功！');
                } else {
                    return $this->error('删除失败！');
                }
            }else{
                return $this->error("系统错误，非法访问！");
            }
        }else{
            return $this->error("系统错误，非法访问！");
        }
    }

    /**
     * Ajax Modify Friendly Link
     */
    public function ajax_edit_link(){
        if(request()->isPost()){
            $data = [];
            $data['id']     = input("post.id", 0, "intval");
            $data['name']   = input("post.name", "", "trim");
            $data['url']    = input("post.url", "", "trim");
            $data['sort']   = input("post.sort", 50, "trim");
            $data['show']   = input("post.show", 1, "intval");
            $data['target'] = input("post.target", 0, "intval");
            $validate = \think\Loader::validate('Link');
            if($validate->scene('edit_link')->check($data)){
                $model = model("Link");
                $_res = $model->update($data);
                if($_res !== false){
                    return $this->success("友情链接修改成功！");
                }else{
                    return $this->error("友情链接修改失败！");
                }
            }else{
                return $this->error($validate->getError());
            }
        }else{
            return $this->error("系统错误，非法访问！");
        }
    }

    /**
     * Custom navigation
     */
    public function navigation(){
        $model = model("Navigation");
        $_res = $model->page_all_navs();
        $replace = [
            'show' => [0 => '否', 1 => '是'],
            'target' => [0 => '否', 1 => '是'],
        ];
        int_to_string($_res['_list'], $replace);
        $this->assign("_list", $_res['_list']);
        $this->assign("_page", $_res['_page']);
        return view('system/navs');
    }

    /**
     * Ajax Add Custom navigation
     */
    public function ajax_add_navigation(){
        if(request()->isPost()){
            $data = [];
            $data['name']   = input("post.name", "", "trim");
            $data['url']    = input("post.url", "", "trim");
            $data['sort']   = input("post.sort", 50, "trim");
            $data['show']   = input("post.show", 1, "intval");
            $data['target'] = input("post.target", 0, "intval");
            $validate = \think\Loader::validate('Navigation');
            if($validate->scene('add_navs')->check($data)){
                $model = model("Navigation");
                $_res = $model->save($data);
                if($_res){
                    return $this->success("自定义导航添加成功！");
                }else{
                    return $this->error("自定义导航添加失败！");
                }
            }else{
                return $this->error($validate->getError());
            }
        }else{
            return $this->error("系统错误，非法访问！");
        }
    }

    /**
     * Ajax Modify Custom navigation
     */
    public function ajax_edit_navigation(){
        if(request()->isPost()){
            $data = [];
            $data['id']     = input("post.id", 0, "intval");
            $data['name']   = input("post.name", "", "trim");
            $data['url']    = input("post.url", "", "trim");
            $data['sort']   = input("post.sort", 50, "trim");
            $data['show']   = input("post.show", 1, "intval");
            $data['target'] = input("post.target", 0, "intval");
            $validate = \think\Loader::validate('Navigation');
            if($validate->scene('edit_navs')->check($data)){
                $model = model("Navigation");
                $_res = $model->update($data);
                if($_res){
                    return $this->success("自定义导航修改成功！");
                }else{
                    return $this->error("自定义导航修改失败！");
                }
            }else{
                return $this->error($validate->getError());
            }
        }else{
            return $this->error("系统错误，非法访问！");
        }
    }

    /**
     * Ajax Delete Custom navigation
     */
    public function ajax_delete_navigation(){
        if(request()->isPost()){
            $id = input("post.id", 0, "intval");
            if($id){
                $model = model("Navigation");
                $map = ['id' => $id];
                if($model->where($map)->delete()){
                    return $this->success('删除成功！');
                } else {
                    return $this->error('删除失败！');
                }
            }else{
                return $this->error("系统错误，非法访问！");
            }
        }else{
            return $this->error("系统错误，非法访问！");
        }
    }

    /**
     * Region List
     */
    public function region(){
        $id = input("id", 0, "intval");
        $model = model("Region");
        $_res = $model->all_regions($id);
        $_info =  $model->region_by_id($id);
        $this->assign('_list', $_res);
        $this->assign('_info', $_info);
        return view("system/regions");
    }

    /**
     * Ajax Add Region
     */
    public function ajax_add_region(){
        if(request()->isPost()){
            $data = [];
            $data['name']   = input("post.name", "", "trim");
            $data['level']   = input("post.level", 1, "intval");
            $data['parent_id'] = input("post.parent_id", 0, "intval");
            $validate = \think\Loader::validate('Region');
            if($validate->scene('add_region')->check($data)){
                $model = model("Region");
                $_res = $model->save($data);
                if($_res){
                    return $this->success("地区添加成功！");
                }else{
                    return $this->error("地区添加失败！");
                }
            }else{
                return $this->error($validate->getError());
            }
        }else{
            return $this->error("系统错误，非法访问！");
        }
    }

    /**
     * Ajax Delete Region
     */
    public function ajax_delete_region(){
        if(request()->isPost()){
            $id = input("post.id", 0, "intval");
            if($id){
                $model = model("Region");
                $child = $model->region_children_count($id);
                if($child > 0){
                    return $this->error("存在下属地区，无法删除！");
                }else{
                    $map = ['id' => $id];
                    if($model->where($map)->delete()){
                        return $this->success('删除成功！');
                    } else {
                        return $this->error('删除失败！');
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