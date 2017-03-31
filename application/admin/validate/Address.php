<?php
/**
 * Address validate
 * @author liangjian
 */

namespace app\admin\validate;
use think\Validate;

class Address extends Validate
{
    protected $rule = [
        'id'        => 'require|number|gt:0',
        'consignee' => 'require|max:32',
        'mobile'    => ['require', 'regex'=>'/^(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/'],
        'province'  => 'gt:0',
        'city'      => 'gt:0',
        'district'  => 'gt:0',
        'twon'      => 'gt:0',
        'address'   => 'require|max:128',
        'zipcode'   => 'number',
        'is_default' => 'number|in:0,1',
        'user_id'   => 'require|number|gt:0|user_exist',
    ];

    protected $message = [
        'id.require'        => '系统错误，非法操作！',
        'id.number'         => '系统错误，非法操作！',
        'id.require'        => '系统错误，非法操作！',
        'consignee.require' => '收货人姓名不能为空！',
        'consignee.max'     => '收货人姓名最大32个字符！',
        'mobile.require'    => '手机号码不能为空！',
        'mobile.regex'      => '手机号码格式错误！',
        'province.gt'       => '请选择所在省！',
        'city.gt'           => '请选择所在市！',
        'district.gt'       => '请选择所在区\县！',
        'twon.gt'           => '请选择所在街道！',
        'address.require'   => '详细地址不能为空！',
        'address.max'       => '详细地址最大128个字符！',
        'zipcode.number'    => '邮政编码格式错误！',
        'is_default.number' => '请选择是否默认收货地址！',
        'is_default.in'		=> '请选择是否默认收货地址！',
        'user_id.require'   => '系统错误，非法操作！',
        'user_id.number'    => '系统错误，非法操作！',
        'user_id.require'   => '系统错误，非法操作！',
    ];

    protected function user_exist($value,$rule,$data){
        $model = model("Member");
        $member = $model->member_by_id($data['user_id']);
        return $member ? true : "系统错误，非法操作！";
    }

    protected $scene = [
        'add' => ['consignee', 'mobile', 'province', 'city', 'district', 'twon', 'address', 'user_id', 'zipcode', 'is_default'],
        'edit' => ['id', 'consignee', 'mobile', 'province', 'city', 'district', 'twon', 'address', 'zipcode', 'is_default'],
    ];
}