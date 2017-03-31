<?php
/**
 * Order Controller
 * @author liangjian
 */

namespace app\admin\controller;
use app\admin\controller\Base;
use think\Db;

class Order extends Base
{
    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * Order lists
     * @return \think\response\View
     */
    public function index(){
        $_res = model("Order")->page_search_orders();
        $replace = [
            'order_status' => [
                0 => '未确认',
                1 => '已确认',
                2 => '已收货',
                3 => '已完成',
                4 => '已取消',
                5 => '已作废',
            ],
            'shipping_status' => [
                0 => '未发货',
                1 => '已发货'
            ],
            'pay_status' => [
                0 => '未支付',
                1 => '已支付'
            ]
        ];
        int_to_string($_res['_list'], $replace);
        $this->assign("_list", $_res['_list']);
        $this->assign("_page", $_res['_page']);
        $this->assign("_map", $_res['_map']);
        return view('order/index');
    }

    /**
     * Show Order info
     * @return \think\response\View
     */
    public function show(){
        $id = input("id", 0, "intval");
        if($id){
            $order = model("Order")->order_by_id($id);
            if($order){
                $regions = model("Region")->all_region_lists();
                $regions = ['0'=>''] + $regions;
                $replace = [
                    'order_status' => [
                        0 => '未确认',
                        1 => '已确认',
                        2 => '已收货',
                        3 => '已完成',
                        4 => '已取消',
                        5 => '已作废',
                    ],
                    'shipping_status' => [0 => '未发货', 1 => '已发货'],
                    'pay_status' => [0 => '未支付', 1 => '已支付'],
                    'province' => $regions,
                    'city' => $regions,
                    'district' => $regions,
                    'twon' => $regions
                ];
                data_translation($order, $replace);
                $member = model("Member")->member_by_id($order['user_id']);
                $replace = [
                    'email_validated' => [0 => '未验证', 1 => '已验证'],
                    'mobile_validated' => [0 => '未验证', 1 => '已验证'],
                    'sex' => [0 => '保密', 1 => '男', 2 => '女'],
                    'lock' => [0 => '否', 1 => '是'],
                    'province' => $regions,
                    'city' => $regions,
                    'district' => $regions
                ];
                data_translation($member, $replace);
                $goods = model("OrderGoods")->goods_by_order_id($id);
                if($order['pay_status'] == 1 && $order['shipping_status'] == 0){
                    //已支付未发货，可修改收货地址
                    $province = model("Region")->region_children();
                    $city = $order['province'] ? model("Region")->region_children($order['province']) : [];
                    $district = $order['city'] ? model("Region")->region_children($order['city']) : [];
                    $twon = $order['district'] ? model("Region")->region_children($order['district']) : [];
                    $region = ['province'=>$province, 'city'=>$city, 'district'=>$district, 'twon'=>$twon];
                    $this->assign("_region", $region);
                }
                $this->assign("_order", $order);
                $this->assign("_member", $member);
                $this->assign("_goods", $goods);
                return view('order/show');
            }else{
                return $this->redirect(url("Order/index"));
            }
        }else{
            return $this->redirect(url("Order/index"));
        }
    }

    /**
     * Ajax delete order
     */
    public function ajax_delete_order(){
        if(request()->isPost()){
            $id = input("post.id", 0, "intval");
            if($id){
                Db::startTrans();
                try{
                    model("Order")->where(['id'=>$id])->delete();
                    model("OrderGoods")->where(['order_id'=>$id])->delete();
                    // 提交事务
                    Db::commit();
                    $_res = true;
                } catch (\Exception $e) {
                    // 回滚事务
                    Db::rollback();
                    //dump($e->getMessage());
                    $_res = false;
                }
                if($_res){
                    return $this->success('订单删除成功！');
                } else {
                    return $this->error('订单删除失败！');
                }
            }else{
                return $this->error("系统错误，非法访问！");
            }
        }else{
            return $this->error("系统错误，非法访问！");
        }
    }

    /**
     * Ajax Order shipping
     * 订单发货
     */
    public function ajax_do_shipping(){
        if(request()->isPost()){
            $data = [];
            $data['id'] = input("post.order_id", 0, "intval");
            $data['shipping_name'] = input("post.name", "", "trim");
            $data['shipping_sn'] = input("post.sn", "", "trim");
            $data['shipping_status'] = 1;
            $data['shipping_time'] = request()->time();
            $validate = \think\Loader::validate('Order');
            if($validate->scene('shipping')->check($data)){
                $_res = model("Order")->update($data);
                if($_res !== false){
                    return $this->success("订单操作成功！");
                }else{
                    return $this->error("订单操作失败！");
                }
            }else{
                return $this->error($validate->getError());
            }
        }else{
            return $this->error("系统错误，非法访问！");
        }
    }

    /**
     * Ajax Confirm Order
     * 确认订单
     */
    public function ajax_confirm_order(){
        if(request()->isPost()){
            $data = [];
            $data['id'] = input("post.order_id", 0, "intval");
            $data['order_status'] = 1;
            $validate = \think\Loader::validate('Order');
            if($validate->scene('confirm')->check($data)){
                $_res = model("Order")->update($data);
                if($_res !== false){
                    return $this->success("订单操作成功！");
                }else{
                    return $this->error("订单操作失败！");
                }
            }else{
                return $this->error($validate->getError());
            }
        }else{
            return $this->error("系统错误，非法访问！");
        }
    }

    /**
     * Ajax edit order shipping address
     * 编辑订单收货地址
     */
    public function ajax_edit_address(){
        if(request()->isPost()){
            $data = [];
            $data['id']         = input("post.order_id", 0, "intval");
            $data['consignee']  = input("post.consignee", "", "trim");
            $data['mobile']     = input("post.mobile", "", "trim");
            $data['province']   = input("post.province", 0, "intval");
            $data['city']       = input("post.city", 0, "intval");
            $data['district']   = input("post.district", 0, "intval");
            $data['twon']       = input("post.twon", 0, "intval");
            $data['address']    = input("post.address", "", "trim");
            $data['zipcode']    = input("post.zipcode", "", "trim");
            $validate = \think\Loader::validate('Order');
            if($validate->scene('address')->check($data)){
                $_res = model("Order")->update($data);
                if($_res){
                    return $this->success('邮寄信息修改成功！');
                } else {
                    return $this->error('邮寄信息修改失败！');
                }
            }else{
                return $this->error($validate->getError());
            }
        }else{
            return $this->error("系统错误，非法访问！");
        }
    }

    /**
     * Ajax invalid order
     * 设置订单为无效订单
     */
    public function ajax_invalid_order(){
        if(request()->isPost()){
            $data = [];
            $data['id'] = input("post.order_id", 0, "intval");
            $data['order_status'] = 5;
            $validate = \think\Loader::validate('Order');
            if($validate->scene('invalid')->check($data)){
                $_res = model("Order")->update($data);
                if($_res !== false){
                    return $this->success("订单操作成功！");
                }else{
                    return $this->error("订单操作失败！");
                }
            }else{
                return $this->error($validate->getError());
            }
        }else{
            return $this->error("系统错误，非法访问！");
        }
    }

    /**
     * Ajax order confirm receipt
     * 订单确认收获
     */
    public function ajax_confirm_receipt(){
        if(request()->isPost()){
            $data = [];
            $data['id'] = input("post.order_id", 0, "intval");
            $data['order_status'] = 2;
            $data['confirm_time'] = request()->time();
            $validate = \think\Loader::validate('Order');
            if($validate->scene('receipt')->check($data)){
                $_res = model("Order")->update($data);
                if($_res !== false){
                    return $this->success("订单操作成功！");
                }else{
                    return $this->error("订单操作失败！");
                }
            }else{
                return $this->error($validate->getError());
            }
        }else{
            return $this->error("系统错误，非法访问！");
        }
    }
}