<?php
/**
 * Config Model
 * @author liangjian
 */

namespace app\admin\model;
use think\Model;

class Config extends Model
{
    /**
     * 所有配置
     */
    public function configs(){
        $objs = $this->all();
        $cfgs = [];
        if($objs){
            foreach($objs as $obj){
                $temp = $obj->getData();
                $cfgs[$temp['name']] = $temp['value'];
            }
        }
        return $cfgs;
    }
}