<?php
/**
 * Goods class validate
 * @author liangjian
 */

namespace app\admin\validate;
use think\Validate;

class Classify extends Validate
{
    protected $rule = [
        'id'    => 'require|number|gt:0',
        'name'  => 'require|unique:goods_classify',
        'sort'  => 'number',
        'hot'   => 'number|in:0,1',
        'show'  => 'number|in:0,1',
    ];

    protected $message = [
        'id.require'	=> '系统错误，非法操作！',
        'id.number'		=> '系统错误，非法操作！',
        'id.gt'			=> '系统错误，非法操作！',
        'name.require'	=> '分类名称不能为空！',
        'name.unique'	=> '分类名称已存在！',
        'sort.number'	=> '排序必须为数字！',
        'hot.number'	=> '请选择是否为热门分类！',
        'hot.in'		=> '请选择是否为热门分类！',
        'show.number'	=> '请选择分类是否显示！',
        'show.in'		=> '请选择分类是否显示！',
    ];

    protected $scene = [
        'add' => ['name', 'sort', 'hot', 'show'],
        'edit' => ['id', 'name', 'sort', 'hot', 'show'],
    ];
}