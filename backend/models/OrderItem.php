<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "order_item".
 *
 * @property int $id
 * @property int $order_id
 * @property string $order_number
 * @property string $brand
 * @property string $number
 * @property string $type
 * @property string $desc
 * @property string $files
 * @property string $create_time
 * @property string $price
 * @property string $pricing_id
 */
class OrderItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id','pricing_id'], 'integer'],
            [['order_number', 'desc', 'files', 'create_time'], 'string', 'max' => 255],
            [['brand', 'number', 'type', 'price'], 'string', 'max' => 45],
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
            'order_number' => 'Order Number',
            'brand' => 'Brand',
            'number' => 'Number',
            'type' => 'Type',
            'desc' => 'Desc',
            'files' => 'Files',
            'pricing_id' => 'Pricing Id',
            'price' => 'Price',
            'create_time' => 'Create Time',
        ];
    }
}
