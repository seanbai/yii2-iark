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
            [['name', 'product_ids', 'user_id'], 'required'],
            [['user_id'], 'integer'],
            [['name', 'product_ids', 'created_at'], 'string', 'max' => 255],
            [['name'], 'unique'],
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
        ];
    }
}
