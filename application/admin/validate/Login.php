<?php
/**
 * Login validate
 * @author liangjian
 */
 
namespace app\admin\validate;
use think\Validate;

class Login extends Validate
{
	protected $rule = [
        'username'  =>  'require|alphaDash',
        'password'	=>  'require',
    ];

    protected $message = [
        'username.require'	 =>	'用户名不能为空！',
        'username.alphaDash' =>	'用户名格式错误！',
        'password.require'	 =>	'密码不能为空！',
    ];
	
	protected $scene = [
        'login'  =>  ['username', 'password'],
    ];
}
