<?php


namespace backend\models;


use yii\db\ActiveRecord;

/**
 *
 * @property int $id
 * @property int $supplier_order_id
 * @property int $order_item_id
 * @property string $brand
 * @property string $number
 * @property string $type
 * @property string $desc
 * @property string $files
 * @property string $price
 * @property string $size
 * @property string $material
 * @property string $att
 * @property string $product_supplier
 * @property string $production_status
 * Class SupplierOrder
 * @package backend\models
 */
class SupplierOrderItem extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'supplier_order_item';
    }
}