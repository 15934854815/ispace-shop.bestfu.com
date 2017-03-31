<?php
/**
 * Article validate
 * @author liangjian
 * @email liangjian@bestfu.com
 */

namespace app\admin\validate;
use think\Validate;

class Article extends Validate
{
    protected $rule = [
        'id'		=> 'require|number|gt:0',
        'title'		=> 'require|max:255',
        'content'   => 'require',
        'class_id'  => 'require|number|gt:0|class_exist',
        'sort'		=> 'number',
        'show'      => 'number|in:0,1',
    ];

    protected $message = [
        'id.require'	=> '系统错误，非法操作！',
        'id.number'		=> '系统错误，非法操作！',
        'id.gt'			=> '系统错误，非法操作！',
        'title.require' => '文章标题不能为空！',
        'title.max'     => '文章标题最大255个字符！',
        'content.require' => '文章内容不能为空！',
        'class_id.require' => '请选择文章分类！',
        'class_id.number' => '请选择文章分类！',
        'class_id.gt'   => '请选择文章分类！',
        'sort.number'	=> '排序必须为数字！',
        'show.number'	=> '请选择分类是否显示！',
        'show.in'		=> '请选择分类是否显示！',
    ];

    protected function class_exist($value,$rule,$data){
        $class = model("ArticleClassify")->class_by_id($data['class_id']);
        return $class ? true : "请选择文章分类！";
    }

    protected $scene = [
        'add' =>  ['title', 'content', 'class_id', 'sort', 'show'],
        'edit' =>  ['id', 'title', 'content', 'class_id', 'sort', 'show'],
    ];
}