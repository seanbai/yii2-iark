<?php


namespace backend\models;


use yii\db\ActiveRecord;
use yii\db\Exception;

/**
 * @property int $id
 * @property string $order_number
 * @property string $date
 * @property string $order_id
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

    public function checkProductionStatus()
    {
        $items = $this->hasMany(SupplierOrderItem::class, ['supplier_order_id' => 'id'])
            ->all();
        $itemCount = count($items);
        $updateComplete = true;
        foreach ($items as $item) {
            /* @var $item SupplierOrderItem */
            if($item->production_status == 1){
                $updateComplete = false;
                break;
            }
        }

        if($updateComplete){
            $this->setAttribute('order_status', 7);
            $this->save();//完成生产

            //更改父订单状态
            $this->parentOrderStatus(7);
        }
    }

    public function parentOrderStatus($status)
    {
        $order = Order::findOne($this->order_id);
        $childOrders = $order->hasMany(SupplierOrder::class, ['order_id' => 'id'])
            ->all();
        $update = true;
        foreach ($childOrders as $childOrder){
            if($childOrder->order_status != $status){
                $update = true;
                break;
            }
        }
        if($update){
            $order->setAttribute('order_status', $status);
        }
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