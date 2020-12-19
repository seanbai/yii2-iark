<?php


namespace backend\models;


use backend\helpers\OrderStatus;
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
 * @property float  $disc_total
 * Class SupplierOrder
 * @package backend\models
 */
class SupplierOrder extends ActiveRecord
{
    /**
     * @var array 不需要同步到主订单的状态列表
     */
    private $_noChangeToOrderStatus = [
        81,
    ];

    /**
     * @var SupplierOrderItem[]
     */
    private $_items;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'supplier_order';
    }

    /**
     * 主订单状态
     *
     * @param $status
     * @return mixed
     */
    private function getMainOrderStatus($status)
    {
        $statusMapping = OrderStatus::getMapping();
        return $statusMapping[$status];
    }

    /**
     * 子订单状态更改
     *
     * @param int $status  子订单状态
     * @param bool $checkFirst 一个子订单状态变更，就更新主订单状态， 默认false
     * @throws Exception
     * @throws \Exception
     */
    public function setStatus($status, $checkFirst = false)
    {
        $db = self::getDb()->beginTransaction();
        try{
            $this->setAttribute('order_status', $status);
            $this->save();
            if(!in_array($status, $this->_noChangeToOrderStatus)){
                //更新对应的主订单状态
                $mainOrderStatus = $this->getMainOrderStatus($status);
                $this->changeParentOrderStatus($status, $mainOrderStatus, $checkFirst);
            }
            $db->commit();
        }catch (\Exception $e){
            $db->rollBack();
            throw $e;
        }
    }


    /**
     * 更新主订单的状态
     *
     * @param int $childStatus 子订单状态
     * @param int  $orderStatus 主订单状态
     * @param bool $checkFirst 一个子订单状态变更，就更新主订单状态， 默认false
     */
    public function changeParentOrderStatus($childStatus, $orderStatus, $checkFirst = false)
    {
        $order = Order::findOne($this->order_id);
        $supplierOrders = $order->hasMany(SupplierOrder::class, ['order_id' => 'id'])
            ->all();
        $update = true;
        foreach ($supplierOrders as $supplierOrder){
            /* @var $supplierOrder $this*/
            $condition = $checkFirst ? $supplierOrder->order_status == $childStatus :
                         $supplierOrder->order_status != $childStatus;
            if($condition){
                $update = false;
                break;
            }
        }
        if($update && ($order->order_status != $orderStatus)){
            $order->setAttribute('order_status', $orderStatus);
            //计算整个订单的总报价
            $total = 0;
            foreach ($supplierOrders as $supplierOrder) {
                $total +=  $supplierOrder->total;
            }
            $order->quote = $total;
            $order->save();
        }
    }

    /**
     * @return array|SupplierOrderItem[]
     */
    public function items()
    {
        if($this->_items === null){
            $this->_items = $this->hasMany(SupplierOrderItem::class, ['supplier_order_id' => 'id'])
                ->all();
        }
        return $this->_items;
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
        $items = $this->items();
        foreach ($items as $item){
            /* @var $item SupplierOrderItem */
            $price = (float) $item->price * $item->number;
            $total += $price;
        }
        $orderStatus = 41; //子订单完成报价
        $this->total = $total;
        $this->quote_time = date('Y-m-d H:i:s');
        $this->setStatus($orderStatus); //所有子订单报价完成，更改主订单的状态为‘报价完成’
    }

    /**
     * @return int
     */
    public function itemCount()
    {
        return count($this->items());
    }

    /**
     * 保存装箱单号
     * @param $packingList
     */
    public function savePackingNumbers($packingList)
    {
        $packingLists = [];
        foreach ($packingList as $packing){
            $packingLists[$packing['id']] = $packing['value'];
        }
        $items = $this->items();
        foreach ($items as $item) {
            $packing = $packingLists[$item->id];
            $item->packing_number = $packing;
            $item->save(false);
        }
    }
}