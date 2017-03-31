<?php
/**
 * Member Controller
 * @author liangjian
 */

namespace app\admin\controller;
use app\admin\controller\Base;
use think\Db;

class Member extends Base
{
    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * Member List
     */
    public function index(){
        $_res = model("Member")->page_all_members();
        $regions = model("Region")->all_region_lists();
        $regions = ['0'=>''] + $regions;
        $replace = [
            'email_validated' => [0 => '未验证', 1 => '已验证'],
            'mobile_validated' => [0 => '未验证', 1 => '已验证'],
            'sex' => [0 => '保密', 1 => '男', 2 => '女'],
            'lock' => [0 => '否', 1 => '是'],
            'province' => $regions,
            'city' => $regions,
            'district' => $regions
        ];
        int_to_string($_res['_list'], $replace);
        $this->assign("_list", $_res['_list']);
        $this->assign("_page", $_res['_page']);
        return view('member/index');
    }

    /**
     * Ajax Edit Member
     */
    public function ajax_edit_member(){
        if(request()->isPost()){
            $data = [];
            $data['user_id'] = input("post.user_id", 0, "intval");
            $data['nickname'] = input("post.nickname", "", "trim");
            $data['email']  = input("post.email", "", "trim");
            $data['mobile'] = input("post.mobile", "", "trim");
            $data['province'] = input("post.province", 0, "intval");
            $data['city']   = input("post.city", 0, "intval");
            $data['district'] = input("post.district", 0, "intval");
            $data['sex']    = input("post.sex", 0, "intval");
            $data['lock']   = input("post.lock", 0, "intval");
            $validate = \think\Loader::validate('Member');
            if($validate->scene('edit_member')->check($data)){
                $model = model("Member");
                $_res = $model->update($data);
                if($_res){
                    return $this->success("会员信息修改成功！");
                }else{
                    return $this->error("会员信息修改失败！");
                }
            }else{
                return $this->error($validate->getError());
            }
        }else{
            return $this->error("系统错误，非法访问！");
        }
    }

    /**
     * Ajax Delete Member
     */
    public function ajax_delete_member(){
        if(request()->isPost()){
            $id = input("post.user_id", 0, "intval");
            if($id){
                Db::startTrans();
                try{
                    $map = ['user_id' => $id];
                    model("Member")->where($map)->delete();
                    model("Address")->where($map)->delete();
                    // 提交事务
                    Db::commit();
                    $_res = true;
                } catch (\Exception $e) {
                    // 回滚事务
                    Db::rollback();
                    //dump($e->getMessage());
                    $_res = false;
                }
                if($_res){
                    return $this->success('会员删除成功！');
                } else {
                    return $this->error('会员删除失败！');
                }
            }else{
                return $this->error("系统错误，非法访问！");
            }
        }else{
            return $this->error("系统错误，非法访问！");
        }
    }

    /**
     * Ajax获取用户地址
     */
    public function ajax_member_address(){
        if(request()->isPost()){
            $id = input("post.user_id", 0, "intval");
            $member = model("Member")->member_by_id($id, "province,city,district");
            if($member){
                $model = model("Region");
                $province = $model->region_children();
                $city = $member['province'] ? $model->region_children($member['province']) : [];
                $district = $member['city'] ? $model->region_children($member['city']) : [];
                return ['province'=>$province, 'city'=>$city, 'district'=>$district];
            }else{
                return [];
            }
        }else{
            return [];
        }
    }

    /**
     * Member shipping address
     */
    public function address(){
        $user_id = input("id", 0, "intval");
        if($user_id){
            if(model("Member")->member_by_id($user_id)){
                $_res = model("Address")->page_all_address($user_id);
                $regions = model("Region")->all_region_lists();
                $province = model("Region")->region_children();
                $regions = ['0'=>''] + $regions;
                $replace = [
                    'province' => $regions,
                    'city' => $regions,
                    'district' => $regions,
                    'twon' => $regions,
                    'is_default' => [0 => '否', 1 => '是'],
                ];
                int_to_string($_res['_list'], $replace);
                $this->assign("_list", $_res['_list']);
                $this->assign("_page", $_res['_page']);
                $this->assign("_data", $province);
                return view('member/address');
            }else{
                return $this->redirect(url("Member/index"));
            }
        }else{
            return $this->redirect(url("Member/index"));
        }
    }

    /**
     * Ajax add member shipping address
     */
    public function ajax_add_address(){
        if(request()->isPost()){
            $data = [];
            $data['consignee']  = input("post.consignee", "", "trim");
            $data['mobile']     = input("post.mobile", "", "trim");
            $data['province']   = input("post.province", 0, "intval");
            $data['city']       = input("post.city", 0, "intval");
            $data['district']   = input("post.district", 0, "intval");
            $data['twon']       = input("post.twon", 0, "intval");
            $data['address']    = input("post.address", "", "trim");
            $data['zipcode']    = input("post.zipcode", "", "trim");
            $data['is_default'] = input("post.is_default", 0, "intval");
            $data['tag']        = input("post.tag", "", "trim");
            $data['user_id']    = input("post.user_id", 0, "intval");
            $validate = \think\Loader::validate('Address');
            if($validate->scene('add')->check($data)){
                Db::startTrans();
                try{
                    $model = model("Address");
                    if($data['is_default'] == 1){
                        $map = ['user_id' => $data['user_id']];
                        $model->where($map)->setField("is_default", 0);
                    }
                    $model->save($data);
                    // 提交事务
                    Db::commit();
                    $_res = true;
                } catch (\Exception $e) {
                    // 回滚事务
                    Db::rollback();
                    //dump($e->getMessage());
                    $_res = false;
                }
                if($_res){
                    return $this->success('收货地址添加成功！');
                } else {
                    return $this->error('收货地址添加失败！');
                }
            }else{
                return $this->error($validate->getError());
            }
        }else{
            return $this->error("系统错误，非法访问！");
        }
    }

    /**
     * Ajax get shipping address information
     */
    public function ajax_get_address(){
        if(request()->isPost()){
            $id = input("post.id", 0, "intval");
            $address = model("Address")->address_by_id($id, "province,city,district");
            if($address){
                $model = model("Region");
                $province = $model->region_children();
                $city = $address['province'] ? $model->region_children($address['province']) : [];
                $district = $address['city'] ? $model->region_children($address['city']) : [];
                $twon = $address['district'] ? $model->region_children($address['district']) : [];
                return ['province'=>$province, 'city'=>$city, 'district'=>$district, 'twon'=>$twon];
            }else{
                return [];
            }
        }else{
            return [];
        }
    }

    /**
     * Ajax edit member shipping address
     */
    public function ajax_edit_address(){
        if(request()->isPost()){
            $data = [];
            $data['id']         = input("post.id", 0, "intval");
            $data['consignee']  = input("post.consignee", "", "trim");
            $data['mobile']     = input("post.mobile", "", "trim");
            $data['province']   = input("post.province", 0, "intval");
            $data['city']       = input("post.city", 0, "intval");
            $data['district']   = input("post.district", 0, "intval");
            $data['twon']       = input("post.twon", 0, "intval");
            $data['address']    = input("post.address", "", "trim");
            $data['zipcode']    = input("post.zipcode", "", "trim");
            $data['is_default'] = input("post.is_default", 0, "intval");
            $data['tag']        = input("post.tag", "", "trim");
            $validate = \think\Loader::validate('Address');
            if($validate->scene('edit')->check($data)){
                Db::startTrans();
                try{
                    $model = model("Address");
                    if($data['is_default'] == 1){
                        $_info = $model->address_by_id($data['id'], 'user_id');
                        $map = ['user_id' => $_info['user_id']];
                        $model->where($map)->setField("is_default", 0);
                    }
                    $model->update($data);
                    // 提交事务
                    Db::commit();
                    $_res = true;
                } catch (\Exception $e) {
                    // 回滚事务
                    Db::rollback();
                    //dump($e->getMessage());
                    $_res = false;
                }
                if($_res){
                    return $this->success('收货地址修改成功！');
                } else {
                    return $this->error('收货地址修改失败！');
                }
            }else{
                return $this->error($validate->getError());
            }
        }else{
            return $this->error("系统错误，非法访问！");
        }
    }

    /**
     * Ajax delete member shipping address
     */
    public function ajax_delete_address(){
        if(request()->isPost()){
            $id = input("post.id", 0, "intval");
            if($id){
                $map = ['id' => $id];
                if(model("Address")->where($map)->delete()){
                    return $this->success('收货地址删除成功！');
                } else {
                    return $this->error('收货地址删除失败！');
                }
            }else{
                return $this->error("系统错误，非法访问！");
            }
        }else{
            return $this->error("系统错误，非法访问！");
        }
    }
}