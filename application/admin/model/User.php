<?php
/**
 * User Model
 * @author liangjian
 */
namespace app\admin\model;

use think\Model;

class User extends Model
{
    protected $table = 'bestfu_admin';

    protected $insert = ['password', 'addtime'];

    protected function setPasswordAttr($value)
    {
        return sp_password($value);
    }

    protected function setAddtimeAttr()
    {
        return request()->time();
    }

    /**
     * 用户登录认证
     * @param  string  $username 用户名
     * @param  string  $password 用户密码
     * @return integer 登录成功-用户ID，登录失败-错误编号
     */
    public function login($username, $password){
        $map = [];
        $map['username'] = $username;
        $map['status'] = 0;

        /* 获取用户数据 */
        $temp = $this->where($map)->find();
        if($temp){
            $user = $temp->getData();
            /* 验证用户密码 */
            if(sp_compare_password($password, $user['password'])){
                //登录成功
                $uid = $user['id'];
                // 更新登录信息
                $this->auto_login($user);
                return $uid ; //登录成功，返回用户UID
            } else {
                return -2; //密码错误
            }
        } else {
            return -1; //用户不存在
        }
    }

    /**
     * 自动登录用户
     * @param  integer $user 用户信息数组
     */
    private function auto_login($user){
        /* 更新登录信息 */
        $data = [
            'lasttime'	=> request()->time(),
            'lastip'	=> request()->ip()
        ];
        $this->save($data,['id' => $user['id']]);

        /* 记录登录SESSION和COOKIES */
        $auth = [
            'id'		=> $user['id'],
            'username'	=> $user['username'],
            'lasttime'	=> $user['lasttime'],
        ];
        if(is_administrator()){
            session(config("admin_auth_key"), $user['id']);
        }
        session(config("user_auth_key"), $user['id']);
        session('user_auth', $auth);
        session('user_auth_sign', data_auth_sign($auth));
    }

    /**
     * 分页获取后台管理员列表
     * @page $size 分页数量
     * @return array
     */
    public function page_all_admins($size = null){
        $admins = [];
        $page = null;
        $size = $size ? $size : config('page_size');
        $objs = $this->field("password", true)->order('id DESC')->paginate($size);
        if($objs){
            foreach($objs as $obj){
                $admins[] = $obj->getData();
            }
            $page = $objs->render();
        }
        return ['_list'=>$admins, '_page'=>$page];
    }
}