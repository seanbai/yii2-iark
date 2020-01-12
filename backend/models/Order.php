<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property string $order_number
 * @property string $status
 * @property string $delivery_time
 * @property string $payment_method
 * @property string $order_user
 * @property string $create_time
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
            [['order_number', 'payment_method', 'order_user'], 'integer'],
            [['delivery_time','status' ,'create_time'], 'string', 'max' => 255],
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
