<?php

namespace backend\helpers;

class ItemStatus
{
    const WAIT = 0;     //待申请
    const HASAPPLIED = 1;     //税金申请
    const HAVETOPAY = 2;     //税金已支付
    const SERVICEBEENAPPILIED = 3;     //服务费申请
    const SERVICEPAY = 4;     //服务费已支付
    const COMPLETE = 5;     //完成


    public static function getType($instance = null){
        $rtn = [
            static::WAIT => '待发起税金申请',
            static::HASAPPLIED => '税金已申请',
            static::HAVETOPAY => '待确认税金',
            static::SERVICEBEENAPPILIED => '服务费已申请',
            static::SERVICEPAY => '待确认服务费',
            static::COMPLETE => '完成',
        ];

        if ($instance !== null)
            $rtn = isset($rtn[$instance]) ? $rtn[$instance] : '';

        return $rtn;
    }
}