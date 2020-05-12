<?php

namespace backend\helpers;

class OrderStatus
{
    /**
     * 主订单状态
     *
     * @param null $code
     * @return array|mixed
     */
    public static function get($code = null)
    {
        $status = [
            0    => '新订单',
            1    => '订单已确认',
            2    => '取消采购',
            3    => '等待报价',
            4    => '已报价',
            5    => '确认报价',
            6    => '定金已付', //采购方
            7    => '定金已收', //平台方
            8    => '生产中',   //供货商确认定金后，开始生产
            9    => '生产完成',
            10   => '尾款已付', //采购方
            11   => '尾款已取', //平台方
            12   => '待提货',   //供货商确认尾款后，变更待提货
            13   => '报关中',   //开始报关，平台方通知采购方支付税金
            15   => '税金已付', //采购方
            16   => '税金已收', //平台方
            17   => '等待入库',
            18   => '待发货',
            19   => '物流运输中',
            20   => '已入库',
            401  => '拒绝报价',
            200  => '订单完成',
        ];
        if ($code) {
            return $status[$code];
        }
        return $status;
    }


    /**
     * 子订单的状态
     *
     * @param null $code
     * @return mixed|string
     */
    public static function getChild($code = null)
    {
        $status = self::get();
        $status = array_merge($status, [
            701  => '定金待收',
            1101 => '尾款待收',
            1601 => '税金待收',
        ]);
        if ($code) {
            return $status[$code];
        }
        return $status;
    }
}