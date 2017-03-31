<?php
/**
 * Navigation validate
 * @author liangjian
 */

namespace app\admin\validate;
use think\Validate;

class Navigation extends Validate
{
    protected $rule = [
        'id'		=> 'require|number|gt:0',
        'name'		=> 'require|unique:navigation',
        //'url'		=> 'require|url',
        'url'		=> 'require',
        'sort'		=> 'number',
        'show'      => 'number|in:0,1',
        'target'    => 'number|in:0,1',
    ];

    protected $message = [
        'id.require'	=> '系统错误，非法操作！',
        'id.number'		=> '系统错误，非法操作！',
        'id.gt'			=> '系统错误，非法操作！',
        'name.require'	=> '导航名称不能为空！',
        'name.unique'	=> '导航名称已存在！',
        'url.require'	=> '链接地址不能为空！',
        //'url.url'		=> '链接地址格式错误！',
        'sort.number'	=> '排序必须为数字！',
        'show.number'	=> '请选择链接是否显示！',
        'show.in'		=> '请选择链接是否显示！',
        'target.number'	=> '请选择链接是否新窗口打开！',
        'target.in'		=> '请选择链接是否新窗口打开！',
    ];

    protected $scene = [
        'add_navs'  =>  ['name', 'url', 'sort', 'show', 'target'],
        'edit_navs'  =>  ['id', 'name', 'url', 'sort', 'show', 'target'],
    ];
}