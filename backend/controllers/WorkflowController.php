<?php

namespace backend\controllers;

use backend\helpers\OrderStatus;
use backend\models\Admin;
use backend\models\Auth;
use backend\models\Create;
use backend\models\Order;
use backend\models\OrderItem;
use backend\models\ProductPushRecord;
use backend\models\SupplierOrder;
use backend\models\SupplierOrderItem;
use backend\models\User;
use common\helpers\Helper;
use common\strategy\Substance;
use Yii;
use yii\db\Expression;
use yii\web\Response;

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
        $query = $this->getQuery($search['where'])->leftJoin(
            'admin u',
            'u.id = order.user'
        );
        // 查询数据条数
        $total = $query->count();
        if ($total) {
            $columns = ['order.*','u.username as owner'];
            $array = $query->select($columns)->offset($search['offset'])->limit($search['limit'])->orderBy($search['orderBy'])->all();
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

        $search['where'] = ['order_status'=> 401];
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
        //订单是完成正确分配工作 是否出现部分报价是平台部分报价是供货商
        $completeAssign = $model->hasCompleteAssignation();
        $first_quote = '';

        $flag = true;
        foreach ($model->getItems() as $k => $item) {
            if ($k == 0) {
                $first_quote = $item['quote_type'];
            } else {
                if ($first_quote != $item['quote_type']) {
                    $flag = false;
                }
            }
        }
        if ($data['status'] == 3 && $completeAssign && $flag) {
            $model->order_status = 3;
            $quote_status = $first_quote;
            $model->quote_status = $quote_status;
            try{
                $this->splitOrder($model,$quote_status);
            }catch (\Exception $exception){
                return $this->error(300, $exception->getMessage());
            }
            if ($model->save()){
                return $this->success('保存成功');
            } else {
                return $this->error(300, Helper::arrayToString($model->getErrors()));
            }
        } else {
            return $this->error(300, '所有产品必须都分配，且不能部分产品是平台报价，部分产品是供货商报价');
        }

    }



    /**
     * 按供应商拆分订单
     *
     * @param Order $order
     */
    private function splitOrder(Order $order,$quote_status)
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
        $supplierOrderAttributes['order_status'] = 31;//等待报价
        $supplierOrderAttributes['create_time'] = $order->create_time;
        $supplierOrderAttributes['order_number'] = $order->order_number;
        $supplierOrderAttributes['quote_status'] = $quote_status;
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
        $quote_type = ($_POST['open'] == 'true') ? 0 : 1;
        $model = OrderItem::findOne(['id'=>intval($_POST['id'])]);
        $model->supplier_id = $_POST['userId'];
        $model->supplier_name = $_POST['name'];
        $model->quote_type = $quote_type;

        if (isset($_POST['price']) && !empty($_POST['price']) && ($quote_type == 1)) {
            $model->price = $_POST['price'];
        } else {
            $model->price = '0';
        }
        if ($model->save()){
            return $this->success();
        }else{
            return $this->error($model->getErrors());
        }
    }

    /**
     * 供应商报价与分配
     *
     * @acl： workflow/assign
     * @return string
     */
    public function actionAssign()
    {
        return $this->render('assign');
    }

    /**
     * 供应商报价与分配-订单列表
     * @acl：workflow/assign-orders
     * @return array
     */
    public function actionAssignOrders()
    {
        $status = 2; //已确认的订单，等待报价

        return $this->getOrders($status);
    }

    /**
     * 报价中
     *
     * @acl： workflow/quote
     * @return string
     */
    public function actionQuote()
    {
        return $this->render('quote');
    }

    /**
     * 报价中-订单列表
     * @acl：workflow/quote-orders
     * @return array
     */
    public function actionQuoteOrders()
    {
        $status = 3;
        return $this->getOrders($status);
    }

    /**
     * 财务收款
     *
     * @acl： workflow/receive
     * @return string
     */
    public function actionReceive()
    {
        return $this->render('receive');
    }

    /**
     * 财务收款-订单列表
     * @acl：workflow/receive-orders
     * @return array
     */
    public function actionReceiveOrders()
    {
        $status = [5,7,12,15,9,10];
        return $this->getOrders($status);
    }


    /**
     * 财务付款
     *
     * @acl： workflow/pay
     * @return string
     */
    public function actionPay()
    {
        return $this->render('pay');
    }

    /**
     * 财务付款
     * //todo 所有子订单都付款完成，主订单付款完成
     * @return array
     */
    public function actionPayment()
    {
        $code = 400;
        $msg = '操作失败';
        $orderId = isset($_POST['subOrderId']) && $_POST['subOrderId'] ? $_POST['subOrderId'] : 0;
        $deposit = isset($_POST['deposit']) && $_POST['deposit'] ? $_POST['deposit'] : 0;
        $balance =  isset($_POST['balance']) && $_POST['balance'] ? $_POST['balance'] : 0;
        $order = SupplierOrder::findOne($orderId);

        if ($order->id) {
            try {
                $mainOrder = Order::findOne($order->order_id);
                if ($mainOrder->order_status <= 9) {//已经收取了定金
                    if ($deposit > 0 && $deposit !== $order->getOldAttribute('deposit')) {
                        $order->setAttribute('deposit', $deposit);
                        $order->setAttribute('order_status', 81);
                        $order->setAttribute('depositDate',date('Y-m-d H:i:s'));
                        $order->save();
                        $code = 0;
                        $msg = '付款成功';
                    }
                } else if ($mainOrder->order_status == 13) {
                    if($balance > 0 && $balance != $order->getOldAttribute('balance')){
                        $order->setAttribute('balance', $balance);
                        $order->setAttribute('order_status', 131);
                        $order->setAttribute('balanceDate',date('Y-m-d H:i:s'));
                        $order->save();
                        $code = 0;
                        $msg = '付款成功';
                    }
                }
            } catch (\Exception  $e){
                $code = 400;
                $msg = '操作失败';
            }
        } else {
            $code = 400;
            $msg = '子订单不存在';
        }

        \Yii::$app->response->format = 'json';
        return [
            'errCode' => $code,
            'errMsg' => $msg
        ];

    }

    /**
     * 财务收款-订单列表
     * @acl：workflow/pay-orders
     * @return array
     */
    public function actionPayOrders()
    {
        //财务确认定金(尾款)已经收到，操作需要需要付款给供货商的订单
        $status = [8,9,13];
        return $this->getOrders($status);
    }


    public function actionChildOrders()
    {
        $parentId = Yii::$app->request->get('parent_id');
        $childOrders = [];
        $order = Order::findOne($parentId);
        if($order){
            $childOrders = $order->getChildOrders();
        }
        //todo 处理数据成如下面结构
        //  $data[] = [
        //            'supplier_name',
        //            'quote_time', //报价时间
        //            'total', //总价
        //            'deposit', //定金
        //            'depositDate',//定金支付时间
        //            'balance', //尾款
        //            'balanceDate' //尾款支付时间
        //        ];

        \Yii::$app->response->format = 'json';
        return [
            'code' => 0,
            'count' => count($childOrders),
            'data'  => $childOrders
        ];
    }

    /**
     * 订单详情
     *
     * @acl workflow/items
     * @return array
     */
    public function actionItems()
    {
        $orderId = \Yii::$app->request->get('id', null);
        $order = Order::findOne($orderId);
        if($order){
            $items = $order->items;
        }else{
            $items = [];
        }
        \Yii::$app->response->format = 'json';
        $data = [
            'code'  => 0,
            'count' => count($items),
            'data'  => $items
        ];
        return $data;
    }

    /**
     * 订单列表
     *
     * @param null $orderStatus
     * @param null $callBack
     * @return array
     */
    private function getOrders($orderStatus = null, $callBack = null)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        if (is_string($orderStatus) && !$orderStatus) {
            $total = 0;
            $orders = [];
        } else {
            $orderStatusArr = OrderStatus::get();
            if (is_array($orderStatus)) {
                $where = ['in', "order_status", $orderStatus];
            } else {
                $where = new Expression('order_status = :order_status', [':order_status' => $orderStatus]);
            }
            try{
                $query = Order::find()
                    ->leftJoin('admin u','u.id = order.user')
                    ->select('order.*, u.username as owner')->where($where);

                $total = $query->count('order.id');
                $limit = $_GET['limit'];
                $offset = ($_GET['page'] - 1) * 10;
                $query->orderBy('id desc')->limit($limit)->offset($offset);
                $orders = $query->asArray()->all();
                if($callBack && is_callable($callBack)){
                    $orders = call_user_func_array($callBack, $orders);
                }
            }catch (\Exception $exception){
                echo $exception->getMessage();die;
            }
        }
        if (!empty($orders)) {
            foreach ($orders as $k => $order) {
                $orders[$k]['order_status'] = $orderStatusArr[$order['order_status']];
            }
        }
        $data = [
            'code'  => 0,
            'count' => $total,
            'data'  => $orders
        ];
        return $data;
    }
    /**
     * @acl workflow/receive-notice
     */
    public function actionReceiveNotice()
    {
        $id = Yii::$app->getRequest()->post('id');
        $balance = Yii::$app->getRequest()->post('balance_notice');
        $deposit = Yii::$app->getRequest()->post('deposit_notice');
        $tax = Yii::$app->getRequest()->post('tax_notice');
        $order = Order::findOne($id);
        \Yii::$app->response->format = Response::FORMAT_JSON;
        if ($order->id) {
            if ($balance && !$order->balance_notice) {
                //发起尾款收取操作
                $order->balance_notice = 1;
                $order->order_status   = 11;//发起收取尾款请求
                $order->save();
            }
            if ($deposit && !$order->deposit_notice) {
                //发起尾款收取操作
                $order->deposit_notice = 1;
                $order->order_status   = 6;//发起收取定金请求
                $order->save();
            }
            if ($tax && !$order->tax_notice) {
                //发起尾款收取操作
                $order->tax_notice = 1;
                $order->order_status   = 14;//发起收取税金请求
                $order->save();
            }
            if ($order->save()){
                return $this->success();
            }else{
                return $this->error(400, Helper::arrayToString($order->getErrors()));
            }
        } else {
            return $this->error(400,'订单不存在');
        }

    }
    /**
     * @acl workflow/receive-confirm
     */
    public function actionReceiveConfirm()
    {
        $id = Yii::$app->getRequest()->post('orderId');
        $model = Order::findOne(['id'=>intval($id)]);
        $receive_deposit = floatval(Yii::$app->getRequest()->post('receive_deposit'));
        $receive_balance = floatval(Yii::$app->getRequest()->post('receive_balance'));
        $tax             = floatval(Yii::$app->getRequest()->post('tax'));
        $receive_tax     = floatval(Yii::$app->getRequest()->post('receive_tax'));
        if ($model->id) {
            //先要确定当前订单状态下，应该保存什么值
            $order_status = $model->order_status;
            switch ($order_status) {
                case 5:
                    $model->order_status = 6;//什么都不用上传
                    break;
                case 7:
                    $model->order_status = 8;
                    //确认支付了定金，保存定金
                    $model->receive_deposit = $receive_deposit;
                    break;
                case 12:
                    $model->order_status = 13;
                    //确认支付了尾款，保存尾款
                    $model->receive_balance = $receive_balance;
                    break;
                case 15:
                    $model->order_status = 16;
                    //确认支付了税金，保存税金
                    $model->receive_tax  = $receive_tax;
                    break;
            }
            if ($model->save()){
                return $this->success();
            }else{
                return $this->error($model->getErrors());
            }
        } else {
            $this->error(400,'订单不存在');
        }

    }
}
