<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property string $order_number
 * @property string $payment_method
 * @property string $create_time
 * @property int $order_status
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order';
    }

    public static function tableItemName()
    {
        return 'order_item';
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_status'], 'integer'],
            [['create_time'], 'string', 'max' => 255],
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
            'payment_method' => 'Payment Method',
            'create_time' => 'Create Time',
            'order_status' => '订单状态',
        ];
    }


    public static function status(){
        $rtn = [
            0 => "申请中",
            1 => "申请中",
        ];
        return $rtn;
    }

    public static function pay(){
        $rtn = [
            1 => '人民币',
            2 => '欧元',
        ];

        return $rtn;
    }
}
