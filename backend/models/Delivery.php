<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "delivery".
 *
 * @property int $id
 * @property string $name
 * @property string $product_ids 产品ids
 * @property int $user_id 提货人员
 * @property string $created_at 提货时间
 * @property int $order_id
 * @property int $order_item_id
 * @property string $project_name
 */
class Delivery extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'delivery';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'product_ids', 'user_id', 'created_at'], 'required'],
            [['user_id', 'order_id', 'order_item_id'], 'integer'],
            [['name', 'product_ids', 'created_at'], 'string', 'max' => 255],
            [['project_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'product_ids' => 'Product Ids',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'order_id' => 'Order ID',
            'order_item_id' => 'Order Item ID',
            'project_name' => 'Project Name',
        ];
    }
}
