<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "tax_service".
 *
 * @property int $id
 * @property string $name
 * @property int $status
 * @property string $product_ids
 * @property string $product_names
 * @property string $wait_tax_amount
 * @property string $confirm_tax_amount
 * @property string $wait_support_amount
 * @property string $confirm_supprot_amount
 * @property string $created_at
 * @property string $update_at
 * @property string $desc
 * @property int $order_id
 */
class TaxService extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tax_service';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['status', 'order_id'], 'integer'],
            [['desc'], 'string'],
            [['name'], 'string', 'max' => 100],
            [['product_ids', 'product_names', 'wait_tax_amount', 'confirm_tax_amount', 'wait_support_amount', 'confirm_supprot_amount', 'created_at', 'update_at'], 'string', 'max' => 255],
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
            'status' => 'Status',
            'product_ids' => 'Product Ids',
            'product_names' => 'Product Names',
            'wait_tax_amount' => 'Wait Tax Amount',
            'confirm_tax_amount' => 'Confirm Tax Amount',
            'wait_support_amount' => 'Wait Support Amount',
            'confirm_supprot_amount' => 'Confirm Supprot Amount',
            'created_at' => 'Created At',
            'update_at' => 'Update At',
            'desc' => 'Desc',
            'order_id' => 'Order ID',
        ];
    }
}
