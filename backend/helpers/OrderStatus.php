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
            1    => '新订单',
            2    => '订单已确认',
            3    => '报价中',   //分配了供应商保存后
            4    => '报价完成', //所有供应商报价完，点击转移报价
            5    => '确认报价', //采购商在订单状态变成3后，方能修改 订单状态变更中的确认或拒绝报价
            6    => '等待收取定金', //当确认报价后，由财务发起定金待收取
            7    => '采购方定金已付', //采购方确认支付定金
            8    => '定金已收取', //财务确认定金已经收到
            9    => '生产中',   //供货商确认定金后，开始生产
            10    => '生产完成', //未发生收取尾款等操作时，当所有子订单都生产完成，则主订单状态变为生成完成，等待财务发起尾款催收，如果期间发生了尾款，则主订单不会进入生产完成状态
            11   => '等待收取尾款', //由财务发起，只要当任何一个供应商的订单状态变为生成完成，便可发起，其后主订单不再进入生成 完成状态
            12   => '采购方支付尾款', //采购方看到主订单需要支付尾款才能去确认已经支付尾款给平台
            13   => '尾款已收取', //财务确认收到尾款，并通过查看供应商订单中，是否已经变成生产完成，只有变为完成的子订单才需要付尾款给供应商，同时放开子订单确认收到尾款的表单
            14   => '待提货',
            14   => '等待收取税金', //财务发起催收税金
            15   => '采购方支付税金', //采购方确认支付税金
            16   => '税金已收取', //财务确认收到税金
            401  => '取消采购',//还未指派供应商
            200  => '订单完成',//当订单变为15后，平台就需要填写报单号，填写物流单号，并修改订单状态为已经完成
        ];
        if ($code) {
            return $status[$code];
        }
        return $status;
    }

    /**
     * 主订单状态 和 子订单状态 映射
     * @return array
     */
    public static function getMapping()
    {
        return [
            31  =>   3,
            41  =>   4,
            81  =>   8,
            91  =>   9,
            101 =>  10,
            201 =>  200,
            402 =>  402,
            403 =>  403,
        ];
    }

    /**
     * 子订单的状态
     *
     * @param null $code
     * @return mixed|string
     */
    public static function getChild()
    {
        $status = [
            31 => '等待报价',//当拆单后，所有子订单变为等待报价
            41 => '报价完成',//每当子订单报价完成，变为报价完成，所有子订单报价完成则修改主订单状态
            81 => '定金待确认',//当财务付款支付了定金后
            91 => '生产中',//当子订单确认收到了定金后，便更改子订单状态为生产中，当第一个子订单状态变为生产中，则主订单状态变为生产中
            101 => '生产完成',//子订单状态一定是从生产中变为生产完成，完成后才能去确认收取尾款
            131 => '尾款待收取',
            132 => '尾款已收取',
            201 => '订单完成',//只有当子订单变为生产完成的时候，才需要去判断子订单是否可以去点击确认收到尾款，如果确认收到了，保存后，应该直接变成订单完成
                            //判断子订单是否可以去点击确认收到尾款，是根据两个条件，1是子订单已经生产完成，2是主订单状态>=12 <=200
            402  => '拒绝报价',//采购方拒绝报价，主订单和旗下所有子订单变为拒绝报价
            403  => '采购商取消采购',//必须在确认报价前，主订单和旗下所有子订单变为采购商取消采购
        ];
        return $status;
    }

    /**
     * 不同账户不同阶段的允许的订单状态
     */
    public function getOrderState()
    {
        $state = [

        ];
    }
}