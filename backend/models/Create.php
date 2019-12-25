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
}
