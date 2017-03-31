<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/**
 * 生成订单号（方式1）
 * 英文字母、年月日、Unix 时间戳和微秒数、随机数。
 * 一个字母对应一个年份，总共16位
 */
if(!function_exists('create_order_sn16'))
{
    function create_order_sn16()
    {
        $year = [
            'A', 'B', 'C', 'D', 'E',
            'F', 'G', 'H', 'I', 'J'
        ];
        $sn = [
            $year[intval(date('Y')) - 2017],
            strtoupper(dechex(date('m'))),
            date('d'),
            substr(time(), -5),
            substr(microtime(), 2, 5),
            sprintf('%02d', rand(0, 99))
        ];
        $sn = implode("", $sn);
        return $sn;
    }
}

/**
 * 生成订单号（方式2）
 * 生成24位唯一订单号码
 * 格式：YYYY-MMDD-HHII-SS-NNNNNNNN-CC
 * YYYY=年份，MM=月份，DD=日期，HH=24格式小时
 * II=分，SS=秒，NNNNNNNN=随机数，CC=检查码
 */
if(!function_exists('create_order_sn20'))
{
    function create_order_sn20(){
        //订单号码主体（YYYYMMDDHHIISSNNNNNNNN）
        $main = date('YmdHis') . rand(1000,9999);
        //订单号码主体长度
        $len = strlen($main);
        $sum = 0;
        for($i=0; $i<$len; $i++){
            $sum += (int)(substr($main,$i,1));
        }
        $cc = str_pad((100 - $sum % 100) % 100, 2, '0', STR_PAD_LEFT);
        //唯一订单号码（YYYYMMDDHHIISSNNNNNNNNCC）
        return "{$main}{$cc}";
    }
}