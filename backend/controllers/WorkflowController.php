<?php

namespace backend\controllers;

use backend\models\Admin;
use backend\models\Auth;
use backend\models\Create;
use backend\models\Order;
use backend\models\OrderItem;
use backend\models\ProductPushRecord;
use backend\models\SupplierOrder;
use backend\models\SupplierOrderItem;
use common\helpers\Helper;
use common\strategy\Substance;
use Yii;

/**
 * Class OrderController My Order 执行操作控制器
 * @package backend\controllers
 */
class WorkflowController extends Controller
{
    /**
     * @var string 定义使用的model
     */
    public $modelClass = 'backend\models\Order';


    public function actionIndex()
    {
        $data = [
            'user' => Admin::getUser(),
            'status' => Order::status(),
            'pay' => Order::pay(),
        ];

        return $this->render('index', $data);
    }

    public function actionList()
    {
        $strategy = Substance::getInstance($this->strategy);
        // 获取查询参数
        $search = $strategy->getRequest(); // 处理查询参数
        $search['field'] = $search['field'] ? $search['field'] : $this->sort;
        $search['orderBy'] = [$search['field'] => $search['sort'] == 'asc' ? SORT_ASC : SORT_DESC];

        if (yii::$app->user->identity->id == 1) {
            $search['where'] = Helper::handleWhere($search['params'], $this->where($search['params']));
        }else{
            $search['where'] = ['user'=> yii::$app->user->identity->id ];
        }

        // 查询数据
        $query = $this->getQuery($search['where']);
        if (YII_DEBUG) $this->arrJson['other'] = $query->createCommand()->getRawSql();

        // 查询数据条数
        $total = $query->count();
        if ($total) {
            $array = $query->offset($search['offset'])->limit($search['limit'])->orderBy($search['orderBy'])->all();
            if ($array) $this->afterSearch($array);
        } else {
            $array = [];
        }

        $data['code'] = 0;
        $data['count'] = $total;
        $data['data'] = $array;
        return json_encode($data);
    }

    /**
     * @return string
     */
    public function actionNewOrder()
    {
        return $this->render('newOrder', []);
    }

    /**
     * 新订单-待平台确认
     */
    public function actionNewOrderList()
    {
        $strategy = Substance::getInstance($this->strategy);
        // 获取查询参数
        $search = $strategy->getRequest(); // 处理查询参数
        $search['field'] = $search['field'] ? $search['field'] : $this->sort;
        $search['orderBy'] = [$search['field'] => $search['sort'] == 'asc' ? SORT_ASC : SORT_DESC];
        $search['limit'] = $_GET['limit'];
        $search['offset'] = ($_GET['page'] - 1) * 10;

        $search['where'] = ['order_status'=> 1];
        // 查询数据
        $query = $this->getQuery($search['where']);
        // 查询数据条数
        $total = $query->count();
        if ($total) {
            $array = $query->offset($search['offset'])->limit($search['limit'])->orderBy($search['orderBy'])->all();
            if ($array) $this->afterSearch($array);
        } else {
            $array = [];
        }

        $data['code'] = 0;
        $data['count'] = $total;
        $data['data'] = $array;
        return json_encode($data);
    }


    /**
     * 已取消订单
     */
    public function actionCancelOrder()
    {
        return $this->render('cancel', []);
    }

    public function actionCancelOrderList()
    {
        $strategy = Substance::getInstance($this->strategy);
        // 获取查询参数
        $search = $strategy->getRequest(); // 处理查询参数
        $search['field'] = $search['field'] ? $search['field'] : $this->sort;
        $search['orderBy'] = [$search['field'] => $search['sort'] == 'asc' ? SORT_ASC : SORT_DESC];
        $search['limit'] = $_GET['limit'];
        $search['offset'] = ($_GET['page'] - 1) * 10;

        $search['where'] = ['order_status'=> 40];
        // 查询数据
        $query = $this->getQuery($search['where']);
        // 查询数据条数
        $total = $query->count();
        if ($total) {
            $array = $query->offset($search['offset'])->limit($search['limit'])->orderBy($search['orderBy'])->all();
            if ($array) $this->afterSearch($array);
        } else {
            $array = [];
        }

        $data['code'] = 0;
        $data['count'] = $total;
        $data['data'] = $array;
        return json_encode($data);
    }


    /****
     * 产品列表页面
     * @return false|string
     */
    public function actionProducts()
    {
        $orderId = $_GET['orderId'];
        $model = OrderItem::find()->where(['order_id'=>$orderId])->asArray()->all();
        $data['code'] = 0;
        $data['count'] = count($model);

        //数据转换一下
        foreach ($model as $key=>$value){
            if (empty($value['supplier_id'])) $model[$key]['supplier_id'] = ' ';
            if (empty($value['supplier_name'])) $model[$key]['supplier_name'] = ' ';
        }
        $data['data'] = $model;

        return json_encode($data);
    }


    /***
     * @return mixed|string
     * 订单状态变更
     */
    public function actionStatus()
    {

        $data = $_POST;
        $model = Order::findOne(['id'=>$data['id']]);

        //是否需要报价
        if (empty($data['pPrice']) && $data['bj'] == 1){
            $status = 2;
            $model->order_status = $status;
        } else if (!empty($data['pPrice']) && $data['bj'] ==2) {
            $model->order_status = 5;
            $model->product_amount = $data['pPrice'];
        } else if ($data['status'] == 7 && !empty($data['deposit'])){
            $model->order_status = 9;
        } else if ($data['status'] == 9){
            $model->order_status = 10;
        } else if ($data['status'] == 14){
            $model->order_status = 15;          //尾款已确认
        }

        if ($model->save()){
            return $this->success('保存成功');
        } else {
            return $this->error(300, Helper::arrayToString($model->getErrors()));
        }

    }


    /***
     * 保存制定用户信息
     */
    public function actionUpdateStatus()
    {
        $data = $_GET;
        $splitOrder = false;
        $model = Order::findOne(['id'=>$data['id']]);
        //订单是完成分配工作
        $completeAssign = $model->hasCompleteAssignation();
        if ($data['status'] == 2 && $completeAssign) {
            $model->order_status = 3;
        } else if ($data['status'] == 9){
            $model->order_status = 10;
        }else if($data['status'] == 3 && $completeAssign){
            //所有item都已分配,状态变更为 => 等待供货商报价
            $model->order_status = 3;
            $splitOrder = true;
        }
        if($splitOrder){
            try{
                $this->splitOrder($model);
            }catch (\Exception $exception){
                return $this->error(300, $exception->getMessage());
            }
        }

        if ($model->save()){
            return $this->success('保存成功');
        } else {
            return $this->error(300, Helper::arrayToString($model->getErrors()));
        }
    }



    /**
     * 按供应商拆分订单
     *
     * @param Order $order
     */
    private function splitOrder(Order $order)
    {
        $supplierOrder = new SupplierOrder;
        $supplierOrderItem = new SupplierOrderItem;
        $orderItems = $order->hasMany(OrderItem::class, ['order_id' => 'id'])->all();
        $splitItems = [];
        foreach ($orderItems as $orderItem){
            /* @var $orderItem OrderItem */
            $supplierOrderItemAttributes = $orderItem->getAttributes(null, [
                    'create_time','supplier_id', 'supplier_name', 'pricing_id', 'order_number', 'order_id', 'id'
                ]);
            $supplierOrderItemAttributes['order_item_id'] = $orderItem->id;
            $splitItems[$orderItem->supplier_id][] = $supplierOrderItemAttributes;
            $suppliers[$orderItem->supplier_id] = $orderItem->supplier_name;
        }
        $supplierOrderAttributes['order_id'] = $order->id;
        $supplierOrderAttributes['date'] = $order->date;
        $supplierOrderAttributes['order_status'] = $order->order_status;
        $supplierOrderAttributes['create_time'] = $order->create_time;
        $supplierOrderAttributes['order_number'] = $order->order_number;
        foreach ($suppliers as $supplierId => $supplierName){
            //save supplier order
            $_supplierOrder = clone  $supplierOrder;
            $supplierOrderAttributes['supplier_id'] = $supplierId;
            $supplierOrderAttributes['supplier_name'] = $supplierName;
            $_supplierOrder->setAttributes($supplierOrderAttributes, false);
            $_supplierOrder->save();

            //save supplier order items
            $supplierItems = $splitItems[$supplierId];
            foreach ($supplierItems as $supplierItemAttributes){
                $supplierItemAttributes['supplier_order_id'] = $_supplierOrder->id;
                $_supplierOrderItem = clone $supplierOrderItem;
                $_supplierOrderItem->setAttributes($supplierItemAttributes, false);
                $_supplierOrderItem->save();
            }
        }
    }

    /***
     * @return mixed|string
     * 订单状态修改页面
     */
    public function actionUpdate()
    {
        $id = $_GET['id'];
        $model = Order::findOne(['id'=>$id]);
        return $this->render('update', [
            'status' => $model['order_status'],
            'id' => $id
        ]);
    }


    /**
     * @return mixed|string
     * 用户信息所有页面
     */
    public function actionUser()
    {
        $user = Admin::find()->select(['id','name'])->where(['role'=>'manufacturer'])->asArray()->all();
        return $this->success($user);
    }

    /**
     * @return mixed|string
     * 获取单个采购商信息
     */
    public function actionOrderUser()
    {
        $user = Admin::find()->where(['id'=>$_GET['id']])->asArray()->one();
        return $this->success($user);
    }

    /**
     * 单个订单进行确认
     */
    public function actionConfirmOrder()
    {
        $id = $_GET['id'];
        $model = Order::findOne(['id'=>$id]);
        $model->order_status = 2;
        if (!$model->save()){
            return $this->error(201,"确认订单失败");
        }
        return $this->success();
    }
    /**
     * 单个订单进行取消
     */
    public function actionCancelOneOrder()
    {
        $id = $_GET['id'];
        $model = Order::findOne(['id'=>$id]);
        $model->order_status = 401;
        if (!$model->save()){
            return $this->error(201,"取消订单失败");
        }
        return $this->success();
    }

    /***
     * @return mixed|string
     * 指定商品保存供货商
     */
    public function actionUpdateUser()
    {
        $model = OrderItem::findOne(['id'=>$_POST['id']]);
        $model->supplier_id = $_POST['userId'];
        $model->supplier_name = $_POST['name'];

        if ($model->save()){
            return $this->success();
        }else{
            return $this->error($model->getErrors());
        }
    }
}
