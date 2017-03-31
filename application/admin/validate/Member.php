<?php
/**
 * Member validate
 * @author liangjian
 */

namespace app\admin\validate;
use think\Validate;

class Member extends Validate
{
    protected $rule = [
        'user_id'   => 'require|number|gt:0',
        'nickname'  => 'length:0,32',
        'email'     => 'email',
        'mobile'    => ['regex'=>'/^(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/'],
        'sex'       => 'number|in:0,1,2',
        'lock'      => 'number|in:0,1',
    ];

    protected $message = [
        'user_id.require'   => '系统错误，非法操作！',
        'user_id.number'    => '系统错误，非法操作！',
        'user_id.gt'    => '系统错误，非法操作！',
        'nickname.length'   => '会员昵称最大32个字符！',
        'email.email'	=> '邮箱格式错误！',
        'mobile.regex'  => '手机格式错误！',
        'sex.number'	=> '请选择会员性别！',
        'sex.in'		=> '请选择会员性别！',
        'lock.number'	=> '请选择会员是否锁定！',
        'lock.in'		=> '请选择会员是否锁定！',
    ];

    protected $scene = [
        'edit_member' => ['user_id', 'nickname', 'email', 'mobile', 'sex', 'lock'],
    ];
}