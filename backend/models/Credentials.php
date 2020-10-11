<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "credentials".
 *
 * @property int $id
 * @property string $order_id
 * @property int $status
 * @property string $file
 * @property int $create_at
 */
class Credentials extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'credentials';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'create_at'], 'integer'],
            [['order_id', 'file'], 'string', 'max' => 255],
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
            'status' => 'Status',
            'file' => 'File',
            'create_at' => 'Create At',
        ];
    }
}
