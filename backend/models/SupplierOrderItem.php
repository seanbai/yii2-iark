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
 * @property string $packing_number
 * @property int $quote_status
 * Class SupplierOrder
 * @package backend\models
 */
class SupplierOrderItem extends ActiveRecord
{
    /**
     * 子订单item 和 订单item 同步字段映射关系
     *  ['子订单item字段' => '订单item字段']
     * @var array
     */
    protected $syncFiledMapping = [
        'price' => 'price',
        'packing_number' => 'packing_number'
    ];

    /**
     * @var OrderItem|null
     */
    private $_orderItem;

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
        $updating = false;
        foreach ($this->syncFiledMapping as $filed => $pFiled) {
            if(isset($changedAttributes[$filed]) && $changedAttributes[$filed]){
                if($orderItem = $this->getOrderItem()){
                    $orderItem->setAttribute($pFiled, $this->getAttribute($filed));
                    $updating = true;
                }
            }
        }
        if($updating){
            $this->getOrderItem()->save(false);
        }
    }

    /**
     * @return OrderItem|null
     */
    public function getOrderItem()
    {
        if($this->_orderItem === null){
            $this->_orderItem = OrderItem::findOne($this->order_item_id);
        }
        return $this->_orderItem;
    }
}