<?php


namespace backend\models;


use yii\db\ActiveRecord;
use yii\db\Exception;

/**
 * @property int $id
 * @property string $order_number
 * @property string $date
 * @property int    $supplier_id
 * @property string $order_status
 * @property string $supplier_name
 * @property string $create_time
 * @property string $quote_time
 * @property float  $total
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

    /**
     * 供应商确认订单报价
     *
     * @throws Exception
     * @throws \Exception
     */
    public function quote()
    {
        $total = 0;
        $items = $this->hasMany(SupplierOrderItem::class, ['supplier_order_id' => 'id']);
        $db = self::getDb()->beginTransaction();
        try{
            foreach ($items as $item){
                /* @var $item SupplierOrderItem */
                $price = (float) $item->price;
                $total += $price;
            }
            $this->total = $total;
            $this->order_status = 5; //已报价
            $this->save();
            $db->commit();
        }catch (\Exception $e){
            $db->rollBack();
            throw $e;
        }
    }
}