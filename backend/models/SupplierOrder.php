<?php


namespace backend\models;


use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $order_number
 * @property string $date
 * @property int $supplier_id
 * @property string $order_status
 * @property string $supplier_name
 * Class SupplierOrder
 * @package backend\models
 */
class SupplierOrder extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'supplier_order';
    }
}