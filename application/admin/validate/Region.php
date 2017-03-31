<?php
/**
 * Region validate
 * @author liangjian
 */

namespace app\admin\validate;
use think\Validate;

class Region extends Validate
{
    protected $rule = [
        'name'		=> 'require|unique:region',
    ];

    protected $message = [
        'name.require'	=> '地区名称不能为空！',
    ];

    protected $scene = [
        'add_region'  =>  ['name'],
    ];
}