<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "message".
 *
 * @property int $id
 * @property int $order_id 订单id
 * @property int $type 订单阶段
 * @property string $text 留言内容
 * @property string $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class Message extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'message';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'type'], 'integer'],
            [['text'], 'string'],
            [['created_at', 'updated_at'], 'string', 'max' => 255],
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
            'type' => 'Type',
            'text' => 'Text',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


}
