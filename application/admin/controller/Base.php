<?php
/**
 * Base Controller
 */
namespace app\admin\controller;
use think\Controller;

class Base extends Controller
{
    public function _initialize()
    {
        define('ADMIN_UID', is_login());
        if(!ADMIN_UID){// 还没登录 跳转到登录页面
            return $this->redirect(url("login/login"));
            exit;
        }
    }
}