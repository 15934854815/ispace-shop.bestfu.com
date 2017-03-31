<?php
/**
 * Order validate
 * @author liangjian
 * @email liangjian@bestfu.com
 */

namespace app\admin\validate;
use think\Validate;

class Order extends Validate
{
    protected $rule = [
        'shipping_name' => 'require|max:64',
        'shipping_sn' => 'require|number|max:64',
        'consignee' => 'require|max:32',
        'mobile'    => ['require', 'regex'=>'/^(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/'],
        'province'  => 'gt:0',
        'city'      => 'gt:0',
        'district'  => 'gt:0',
        'twon'      => 'gt:0',
        'address'   => 'require|max:128',
        'zipcode'   => 'number',
        'id' => 'require|number|gt:0',
    ];

    protected $message = [
        'id.require'        => '系统错误，非法操作！',
        'id.number'         => '系统错误，非法操作！',
        'id.require'        => '系统错误，非法操作！',
        'shipping_name.require' => '快递公司不能为空！',
        'shipping_name.max'     => '快递公司最大64个字符！',
        'shipping_sn.require'   => '快递单号不能为空！',
        'shipping_sn.max'       => '快递单号最大64个字符！',
        'shipping_sn.number'    => '快递单号必须为数字！',
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
    ];

    /**
     * 订单是否已经发货
     */
    protected function is_shipping($value, $rule, $data){
        $map = ['id' => $data['id']];
        $shipping = model("Order")->where($map)->value("shipping_status");
        return $shipping == 0 ? true : "订单无法重复发货！";
    }

    /**
     * 订单是否已经确认
     */
    protected function is_confirm($value, $rule, $data){
        $map = ['id' => $data['id']];
        $confirm = model("Order")->where($map)->value("order_status");
        return $confirm == 0 ? true : "该订单无法确认！";
    }

    /**
     * 订单收获地址是否可更改
     */
    protected function is_deliver($value, $rule, $data){
        $map = ['id' => $data['id']];
        $shipping = model("Order")->where($map)->value("shipping_status");
        return $shipping == 0 ? true : "订单已发货，无法修改！";
    }

    /**
     * 订单是否已经是无效订单
     */
    protected function is_invalid($value, $rule, $data){
        $map = ['id' => $data['id']];
        $shipping = model("Order")->where($map)->value("order_status");
        return $shipping == 5 ? "此订单已作废！" : true;
    }

    /**
     * 订单是否确认收货
     */
    protected function is_receipt($value, $rule, $data){
        $map = ['id' => $data['id']];
        $shipping = model("Order")->where($map)->value("order_status");
        return $shipping == 2 ? "此订单已作废！" : true;
    }

    protected $scene = [
        'shipping' => ['id'=>'require|number|gt:0|is_shipping', 'shipping_name', 'shipping_sn'],
        'confirm' => ['id'=>'require|number|gt:0|is_confirm'],
        'address' => ['id'=>'require|number|gt:0|is_deliver', 'consignee', 'mobile', 'province', 'city', 'district', 'twon', 'address', 'zipcode'],
        'invalid' => ['id'=>'require|number|gt:0|is_invalid'],
        'receipt' => ['id'=>'require|number|gt:0|is_receipt']
    ];
}