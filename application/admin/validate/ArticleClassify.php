<?php
/**
 * Article class validate
 * @author liangjian
 */

namespace app\admin\validate;
use think\Validate;

class ArticleClassify extends Validate
{
    protected $rule = [
        'id'		=> 'require|number|gt:0',
        'name'		=> 'require|unique:article_classify',
        'sort'		=> 'number',
        'show'      => 'number|in:0,1',
    ];

    protected $message = [
        'id.require'	=> '系统错误，非法操作！',
        'id.number'		=> '系统错误，非法操作！',
        'id.gt'			=> '系统错误，非法操作！',
        'name.require'	=> '分类名称不能为空！',
        'name.unique'	=> '分类名称已存在！',
        'sort.number'	=> '排序必须为数字！',
        'show.number'	=> '请选择分类是否显示！',
        'show.in'		=> '请选择分类是否显示！',
    ];

    protected $scene = [
        'add' =>  ['name', 'sort', 'show'],
        'edit' => ['id', 'name', 'sort', 'show']
    ];
}