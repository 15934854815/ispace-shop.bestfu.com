<?php
/**
 * Order Model
 * @author liangjian
 * @email liangjian@bestfu.com
 */

namespace app\admin\model;
use think\Model;

class Order extends Model
{
    /**
     * 分页获取订单列表
     * @param $size 分页数量
     * @return array
     */
    public function page_search_orders($size = null){
        $orders = [];
        $page = null;
        $size = $size ? $size : config('page_size');
        $param = $this->_handle_search_param();
        $objs = $this
                    ->where($param['map'])
                    ->order('id DESC')
                    ->paginate($size, false, ['query'=>$param['query']]);
        if($objs){
            foreach($objs as $obj){
                $orders[] = $obj->getData();
            }
            $page = $objs->render();
        }
        return ['_list'=>$orders, '_page'=>$page, '_map'=>$param['input']];
    }

    /**
     * 处理查询参数
     * @return array
     */
    private function _handle_search_param(){
        $search = [];
        //$query = [];
        $param = input("get.query");
        parse_str(base64_decode($param), $input);
        if($input){
            foreach ($input as $key=>&$val){
                if(trim($val) == ""){
                    unset($input[$key]);
                }
            }
            if(isset($input['_p1'])){
                $search['order_sn'] = $input['_p1'];
                //$query['_p1'] = $input['_p1'];
            }
            if(isset($input['_p2'])){
                $search['consignee'] = ['LIKE',"%{$input['_p2']}%"];
                //$query['_p2'] = $input['_p2'];
            }
            if(isset($input['_p3']) && isset($input['_p4'])){
                $search['total_amount'] = ['BETWEEN', [$input['_p3'], $input['_p4']]];
                //$query['_p3'] = $input['_p3'];
                //$query['_p4'] = $input['_p4'];
            }elseif (isset($input['_p3'])){
                $search['total_amount'] = ['EGT', $input['_p3']];
                //$query['_p3'] = $input['_p3'];
            }elseif (isset($input['_p4'])){
                $search['total_amount'] = ['ELT', $input['_p4']];
                //$query['_p4'] = $input['_p4'];
            }
            if(isset($input['_p5']) && isset($input['_p6'])){
                $search['addtime'] = ['BETWEEN', [strtotime($input['_p5']), strtotime($input['_p6'])]];
                //$query['_p5'] = $input['_p5'];
                //$query['_p6'] = $input['_p6'];
            }elseif (isset($input['_p5'])){
                $search['addtime'] = ['EGT', strtotime($input['_p5'])];
                //$query['_p5'] = $input['_p5'];
            }elseif (isset($input['_p6'])){
                $search['addtime'] = ['ELT', strtotime($input['_p6'])];
                //$query['_p6'] = $input['_p6'];
            }
            if(isset($input['_p7'])){
                $search['order_status'] = $input['_p7'];
                //$query['_p7'] = $input['_p7'];
            }
            if(isset($input['_p8'])){
                $search['pay_status'] = $input['_p8'];
                //$query['_p8'] = $input['_p8'];
            }
            if(isset($input['_p9'])){
                $search['shipping_status'] = $input['_p9'];
                //$query['_p9'] = $input['_p9'];
            }
            if(isset($input['_p10'])){
                $search['pay_name'] = $input['_p10'];
                //$query['_p10'] = $input['_p10'];
            }
        }
        return ['map' => $search, 'query' => ['query'=>$param], 'input' => $input];
    }

    /**
     * 根据ID获取订单信息
     * @param int $id 订单ID
     * @param string $field 查询字段
     * @return array
     */
    public function order_by_id($id=0, $field='*'){
        $order = [];
        if($id){
            $map = ['id' => $id];
            $obj = $this->field($field)->where($map)->find();
            if($obj){
                $order = $obj->getData();
            }
        }
        return $order;
    }
}