<?php

namespace backend\helpers;

class ItemStatus
{
    const WAIT = 0;     //待申请
    const HASAPPLIED = 1;     //服务费已申请
    const HAVETOPAY = 2;     //待确认服务费
    const confirm = 3;     //服务费已确认

    public static function getType($instance = null){
        $rtn = [
            static::WAIT => '待发起服务费申请',
            static::HASAPPLIED => '服务费已申请',
            static::HAVETOPAY => '待确认服务费',
            static::confirm => '已确认服务费',
        ];

        if ($instance !== null)
            $rtn = isset($rtn[$instance]) ? $rtn[$instance] : '';

        return $rtn;
    }
}