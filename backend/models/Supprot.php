<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "supprot".
 *
 * @property int $id
 * @property int $order_id
 * @property string $charge_amount 应收金额
 * @property string $confirm_amout 实收金额
 * @property string $created_at 申请时间
 * @property string $desc 备注信息
 * @property int $is_ charge 是否需要收取服务费
 */
class Supprot extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'supprot';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'is_ charge'], 'integer'],
            [['desc'], 'string'],
            [['charge_amount', 'confirm_amout'], 'string', 'max' => 50],
            [['created_at'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'charge_amount' => 'Charge Amount',
            'confirm_amout' => 'Confirm Amout',
            'created_at' => 'Created At',
            'desc' => 'Desc',
            'is_ charge' => 'Is  Charge',
        ];
    }
}
