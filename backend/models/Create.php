<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property int $order_number
 * @property int $status
 * @property string $delivery_time
 * @property int $payment_method
 * @property int $order_user
 * @property string $create_time
 */
class Create extends \yii\db\ActiveRecord
{

    /** 状态 */
    const TYPE_ONE = 1;
    const TYPE_TWO = 2;
    const TYPE_THREE = 3;
    const TYPE_FOUR = 4;
    const TYPE_FIVES = 5;
    const TYPE_SIX = 6;
    const TYPE_SEVEN = 7;
    const TYPE_EIGHT = 8;
    const TYPE_NING = 9;
    const TYPE_TEN = 10;
    const TYPE_ELEVEN = 11;
    const TYPE_TWELVE = 12;
    const TYPE_THIRTEEN = 13;
    const TYPE_FOURTEEN = 14;



    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'create';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_number', 'status', 'payment_method', 'order_user'], 'integer'],
            [['delivery_time', 'create_time'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_number' => 'Order Number',
            'status' => 'Status',
            'delivery_time' => 'Delivery Time',
            'payment_method' => 'Payment Method',
            'order_user' => 'Order User',
            'create_time' => 'Create Time',
        ];
    }

    public static function getData($instance = null)
    {
        $rtn = [
            static::TYPE_ONE => '待平台处理',
            static::TYPE_TWO => '报价中',
            static::TYPE_THREE => '待采购商付款',
            static::TYPE_FOUR => '待平台收款确认',
            static::TYPE_FIVES => '制造商生成中',
            static::TYPE_SIX => '制造完成,待付尾款',
            static::TYPE_SEVEN => '待平台尾款确认',
            static::TYPE_EIGHT => '工厂提货',
            static::TYPE_NING => '报关',
            static::TYPE_TEN => '税金',
            static::TYPE_ELEVEN => '待平台税金确认',
            static::TYPE_TWELVE => '抵达入库',
            static::TYPE_THIRTEEN=> '拒接该报价',
            static::TYPE_FOURTEEN => '订单完成',
        ];
        if ($instance !== null)
            $rtn = isset($rtn[$instance]) ? $rtn[$instance] : '';

        return $rtn;

    }



}
