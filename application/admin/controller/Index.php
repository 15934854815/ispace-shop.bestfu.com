<?php
/**
 * Index Controller
 */
namespace app\admin\controller;
use app\admin\controller\Base;

class Index extends Base
{
    public function _initialize()
    {
        parent::_initialize();
    }

    public function index()
    {
        $dd = sp_password('123456');
        dump($dd);
        dump($_SESSION);
        dump(date("Y-m-d H:i:s u"));
        $order_sn = create_order_sn16();
        dump($order_sn);
        $order_sn = create_order_sn20();
        dump($order_sn);
    }

    /**
     * 获取地区列表
     */
    public function ajax_region_children($id=0){
        $model = model("Region");
        $children = array();
        if($id){
            $children = $model->region_children($id);
        }
        $this->success($children);
    }
}
