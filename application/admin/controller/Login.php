<?php
/**
 * Login Controller
 * @author liangjian
 */
namespace app\admin\controller;
use think\Controller;

class Login extends Controller
{
    public function login()
    {
        if (is_login()) {
            return $this->redirect(url("index/index"));
            exit;
        } else {
            if (request()->isPost()) {
                $data = [];
                $data['username'] = input("username", "", "trim");
                $data['password'] = input("password", "", "trim");
                $validate = \think\Loader::validate('Login');
                if($validate->scene('login')->check($data)){
                    $model = model("User");
                    $uid = $model->login($data['username'], $data['password']);
                    if(0 < $uid){ // 登录成功，$uid 为登录的 UID
                        //跳转到登录前页面
                        return $this->success('登录成功！', url('index/index'));
                    } else { //登录失败
                        switch($uid) {
                            case -1: $error = '用户不存在或已禁用！'; break; //系统级别禁用
                            case -2: $error = '密码错误！'; break;
                            default: $error = '未知错误！'; break; // 0-接口参数错误
                        }
                        return $this->error($error);
                    }
                }else{
                    return $this->error($validate->getError());
                }
            } else {
                return view('login/login');
            }
        }
    }
}