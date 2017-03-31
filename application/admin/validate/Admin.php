<?php
/**
 * Admin validate
 * @author liangjian
 */

namespace app\admin\validate;
use think\Validate;

class Admin extends Validate
{
    protected $rule = [
        'id'			=> 'require|number|gt:0',
        'username'		=> 'require|unique:admin|alphaNum|length:4,32',
        'password'		=> 'require',
        'repassword'	=> 'require|confirm:password',
        'repwd'         => 'requireWith:pwd|confirm:pwd',
        'status'		=> 'number|in:0,1',
    ];

    protected $message = [
        'id.require'		=> '系统错误，非法操作！',
        'id.number'		    => '系统错误，非法操作！',
        'id.gt'			    => '系统错误，非法操作！',
        'username.require'	=> '用户名不能为空！',
        'username.unique'	=> '用户名已存在！',
        'username.alphaNum'	=> '用户名格式错误！',
        'username.length'	=> '用户名格式错误！',
        'password.require'	=> '密码不能为空！',
        'repassword.require' => '请输入确认密码！',
        'repassword.confirm' => '俩次输入密码不一致！',
        'repwd.requireWith' => '请输入确认密码！',
        'repwd.confirm'     => '俩次输入密码不一致！',
        'status.number'		=> '系统错误，非法操作！',
        'status.in'			=> '系统错误，非法操作！',
    ];

    protected $scene = [
        'add_admin'	=> ['username', 'password', 'repassword', 'status'],
        'edit_admin' => ['id', 'username', 'repwd', 'status'],
    ];
}