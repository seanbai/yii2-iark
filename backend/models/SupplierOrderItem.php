<?php


namespace backend\models;


use yii\db\ActiveRecord;
use yii\db\StaleObjectException;

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
 * @property string $origin_price
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


    /**
     * @param bool $insert
     *
     * /**
     * $changedAttributes = [
     *     $attribute  => $value
     * ]
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if(isset($changedAttributes['price']) && $changedAttributes['price']){
            if($orderItem = $this->getOrderItem()){
                $orderItem->setAttribute('price', $this->price);
                $orderItem->save(false);
            }
        }
    }

    /**
     * @return OrderItem|null
     */
    public function getOrderItem()
    {
        return OrderItem::findOne($this->order_item_id);
    }
}