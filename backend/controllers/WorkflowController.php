<?php

namespace backend\controllers;

use backend\helpers\OrderStatus;
use backend\models\Admin;
use backend\models\Auth;
use backend\models\Create;
use backend\models\Delivery;
use backend\models\Order;
use backend\models\OrderComment;
use backend\models\OrderItem;
use backend\models\ProductPushRecord;
use backend\models\SupplierOrder;
use backend\models\SupplierOrderItem;
use backend\models\Supprot;
use backend\models\TaxService;
use backend\models\User;
use common\helpers\Helper;
use common\strategy\Substance;
use Yii;
use yii\db\Expression;
use yii\rbac\Item;
use yii\web\Response;
use backend\helpers\ItemStatus;

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
            'user' =>   Admin::getUser(),
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
     * 新订单-待平台确认-设计师页面
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
        $loginId = yii::$app->user->identity->id;
        $query = $this->getQuery($search['where'])->leftJoin(
            'admin u',
            "u.id = order.user"
        );
        // 查询数据条数
        $total = $query->count();
        if ($total) {
            $columns = ['order.*','u.username as owner', 'u.designer as designer'];
            $array = $query->select($columns)->offset($search['offset'])->limit($search['limit'])->orderBy($search['orderBy'])->all();
            if ($array) $this->afterSearch($array);
        } else {
            $array = [];
        }

        foreach ($array as $key => $item) {
            if ($item['designer'] != $loginId && $loginId != 1) {
                unset($array[$key]);
            }
        }

        $data['code'] = 0;
        $data['count'] = count($array);
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
        //订单是完成正确分配工作
        $completeAssign = $model->hasCompleteAssignation();

        if ($data['status'] == 3 && $completeAssign) {
            //如果所有的子订单都被平台报价了，则总订单状态变为4,否则订单状态变为3

            try{
                $status = $this->splitOrder($model);
            }catch (\Exception $exception){
                return $this->error(300, $exception->getMessage());
            }
            if ($status) {
                //计算订单总价
                $supplierOrders = $model->hasMany(SupplierOrder::class, ['order_id' => 'id'])
                    ->all();
                $total = 0;
                foreach ($supplierOrders as $supplierOrder) {
                    $total +=  $supplierOrder->total;
                }
                $model->quote = $total;
            }
            $model->order_status = $status?4:3;
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
    private function splitOrder(Order $order)
    {
        $supplierOrder = new SupplierOrder;
        $supplierOrderItem = new SupplierOrderItem;
        $orderItems = $order->hasMany(OrderItem::class, ['order_id' => 'id'])->all();
        $splitItems = [];
        $finalStatus = [];
        foreach ($orderItems as $orderItem){
            /* @var $orderItem OrderItem */
            $supplierOrderItemAttributes = $orderItem->getAttributes(null, [
                'create_time','supplier_id', 'supplier_name', 'pricing_id', 'order_number', 'order_id', 'id'
            ]);
            $supplierOrderItemAttributes['order_item_id'] = $orderItem->id;
            $supplierOrderItemAttributes['quote_status'] = $orderItem->price>0?1:0;
            $splitItems[$orderItem->supplier_id][] = $supplierOrderItemAttributes;
            $suppliers[$orderItem->supplier_id] = $orderItem->supplier_name;
        }
        $supplierOrderAttributes['order_id'] = $order->id;
        $supplierOrderAttributes['date'] = $order->date;

        $supplierOrderAttributes['create_time'] = $order->create_time;
        $supplierOrderAttributes['order_number'] = $order->order_number;
//        $supplierOrderAttributes['quote_status'] = $quote_status;
        $setup = 1;
        foreach ($suppliers as $supplierId => $supplierName){
            //save supplier order
            $_supplierOrder = clone  $supplierOrder;
            $supplierOrderAttributes['supplier_id']   = $supplierId;
            $supplierItems = $splitItems[$supplierId];
            $flag = true;
            $i=0;
            $quote_status=0;

            foreach ($supplierItems as $value) {
                if ($i == 0) {
                    $quote_status = $value['quote_status'];
                } else {
                    if ($value['quote_status'] != $quote_status) {
                        $flag = false;
                        break;
                    }
                }
                $i++;
            }
            if (!$flag) {
                $supplierOrderAttributes['order_status'] = 31;//等待报价
            } else {
                if ($quote_status) {
                    $supplierOrderAttributes['order_status'] = 41;//子订单完全是平台报价
                    //计算子订单总价
                    $total = 0;
                    foreach ($supplierItems as $v) {
                        $price = (float)$v['price']* $v['number'];
                        $total += $price;
                    }
                    $supplierOrderAttributes['total'] = $total;
                    $supplierOrderAttributes['quote_time'] = date('Y-m-d H:i:s');
                } else {
                    $supplierOrderAttributes['order_status'] = 31;//子订单完全是供应商报价
                }
            }
            $finalStatus[] = $supplierOrderAttributes['order_status'];
            $supplierOrderAttributes['supplier_name'] = $supplierName;
            $supplierOrderAttributes['order_number']  = $order->order_number.'-'.$setup;
            $_supplierOrder->setAttributes($supplierOrderAttributes, false);
            $_supplierOrder->save();

            //save supplier order items

            foreach ($supplierItems as $supplierItemAttributes){
                $supplierItemAttributes['supplier_order_id'] = $_supplierOrder->id;
                $_supplierOrderItem = clone $supplierOrderItem;
                $_supplierOrderItem->setAttributes($supplierItemAttributes, false);
                $_supplierOrderItem->save();
            }
            $setup++;
        }
        if (in_array(31,$finalStatus)) {
            return 0;
        } else {
            return 1;
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

        $originPrice = $_POST['price'] ?? '0';
        $discountPrice = $price = 0;
        $model->origin_price = $originPrice; //单价
        $model->price = 0;
        $model->disc_price = 0;


       if ($originPrice > 0 && ($quote_type == 1)) {
           $user = Admin::find()->where(['id' => $_POST['userId']])->asArray()->one();

           $offrate = !empty($user['off']) ? $user['off'] : 0;               //供货商价格
           $discountrate = !empty($user['discount']) ? $user['discount'] : 0;               //供货商价格

           $price = (string) $originPrice * (1 - ($offrate / 100)); //供货折扣/采购折扣价
           $model->price = $price;
           $discountPrice = (string) $originPrice * (1 - ($discountrate / 100)); //供货折扣/采购折扣价
           $model->disc_price = $discountPrice;
       }
       if (!$model->save()) return $this->error($model->getErrors());

       return $this->success([
           'origin_price'    => $originPrice,          //单价
           'discount_price'  => $discountPrice,        //采购折扣价
           'price'           => $price                 //供货商折扣价
       ]);
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
        $status = [3,4];
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
        $status = [5,7,12,15,9,10,14];
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
        $depositFile = $_POST['deposit-upload-file'] ?? null;
        $balanceFile = $_POST['balance-upload-file'] ?? null;
        $balance =  isset($_POST['balance']) && $_POST['balance'] ? $_POST['balance'] : 0;
        $comment =  isset($_POST['info']) && $_POST['info'] ? $_POST['info'] : '';
        $order = SupplierOrder::findOne($orderId);
        if ($order->id) {
            try {
                $mainOrder = Order::findOne($order->order_id);
                if ($mainOrder->order_status <= 9) {//已经收取了定金
                    if ($deposit > 0 && $deposit !== $order->getOldAttribute('deposit')) {
                        if(!$depositFile && ($order->getAttribute('order_status') < 81)){
                            \Yii::$app->response->format = 'json';
                            return [
                                'errCode' => 400,
                                'errMsg' => '请上传定金付款凭证附件'
                            ];
                        }else{
                            $depositFile = $depositFile ?: $order->deposit_file;
                            $order->setAttribute('deposit_file', $depositFile);
                        }
                        $order->setAttribute('deposit', $deposit);
                        $order->setAttribute('order_status', 81);
                        $order->setAttribute('depositDate',date('Y-m-d H:i:s'));
                        $order->save();
                        $code = 0;
                        $msg = '付款成功';
                    }
                } else if ($mainOrder->order_status == 13) {
                    if($balance > 0 && $balance != $order->getOldAttribute('balance')){
                        if(!$balanceFile && ($order->getAttribute('order_status') < 131)){
                            \Yii::$app->response->format = 'json';
                            return [
                                'errCode' => 400,
                                'errMsg' => '请上传定金付款凭证附件'
                            ];
                        }else{
                            $balanceFile = $balanceFile ?: $order->balance_file;
                            $order->setAttribute('balance_file', $balanceFile);
                        }
                        $order->setAttribute('balance', $balance);
                        $order->setAttribute('balance_file', $balanceFile);
                        $order->setAttribute('order_status', 131);
                        $order->setAttribute('balanceDate',date('Y-m-d H:i:s'));
                        $order->save();
                        $code = 0;
                        $msg = '付款成功';
                    }
                }
                if($comment){
                    try{
                        $commentOb = new OrderComment();
                        $commentOb->setAttribute('supplier_order_id', $orderId);
                        $commentOb->setAttribute('status', $order->order_status);
                        $commentOb->setAttribute('comment', $comment);
                        $commentOb->save();
                    }catch (\Exception $exception){}
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
            array_walk($childOrders, function (&$value){
                array_change_key_case($value, CASE_LOWER );
            });
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

        foreach ($childOrders as $key=>$item){
            $originalPrice = 0;
            $total = OrderItem::find()->where(['order_id' => $item['order_id'], 'supplier_id' => $item['supplier_id']])->asArray()->all();
            if ($total) $originalPrice = $this->handleOrderPrice($total);
            $childOrders[$key]['original_price'] = $originalPrice;
        }

        \Yii::$app->response->format = 'json';
        return [
            'code' => 0,
            'count' => count($childOrders),
            'data'  => $childOrders
        ];
    }

    protected function handleOrderPrice($items)
    {
        $total = 0;
        foreach ($items as $item) {
            $total += $item['origin_price'];
        }
        return $total;
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


    /***
     * @return array
     */
    public function actionPickItems()
    {
        $ids = \Yii::$app->request->get('ids', null);
        $ids = explode(',', substr($ids,0,strlen($ids)-1));

        $productItems = SupplierOrderItem::find()
            ->where(['id' => $ids])
            ->asArray()->all();

        \Yii::$app->response->format = 'json';
        $data = [
            'code'  => 0,
            'count' => count($productItems),
            'data'  => $productItems
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
//                case 5:
//                    $model->order_status = 6;//什么都不用上传
//                    break;
                case 7:
                    if ($receive_deposit) {
                        $model->order_status = 8;
                        //确认支付了定金，保存定金
                        $model->receive_deposit = $receive_deposit;
                    }
                    break;
                case 12:
                    if ($receive_balance) {
                        $model->order_status = 13;
                        //确认支付了尾款，保存尾款
                        $model->receive_balance = $receive_balance;
                    }
                    break;
                case 15:
                    if ($receive_tax && $tax) {
                        $model->order_status = 16;
                        //确认支付了税金，保存税金
                        $model->tax = $tax;
                        $model->receive_tax = $receive_tax;
                    }
                    break;
                default :
                    return $this->error(201, "当前状态异常，不能操作其它数据");
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

    /**
     * 待提货页面
     * @return string
     */
    public function actionWaitPick()
    {
        return $this->render('watingpick');
    }

    /**
     * 待提货页面产品数据
     * @return false|string
     */
    public function actionWaitPickList()
    {
        $status = 141;  //子订单，已收取尾款
        //获取子订单状态为已支付的订单
        $model = (new \yii\db\Query())
            ->select('a.*, u.order_status, c.project_name, c.package, c.order_number, c.id as order_id')
            ->from('supplier_order_item AS a')
            ->leftJoin('supplier_order AS u','a.supplier_order_id = u.id')
            ->leftJoin('order AS c','u.order_id = c.id')
            ->where(['u.order_status' => $status])
            ->andWhere(['a.quote_status' => 1])
            ->orderBy('a.id DESC')
            ->All();

        $data = [
            'code'  => 0,
            'count' => count($model),
            'data'  => $model
        ];
        return json_encode($data);
    }

    /**
     * 生成提货单号
     */
    public function actionCreatePick()
    {
        $data = $_POST['ids'];
        $model_tmp = new Delivery();
        $numberName = (string)"TH-".date("YmdHis", time());
        foreach ($data as $datum) {
            $model = clone $model_tmp;
            $model->name = $numberName;
            $model->product_ids = $datum['id'];
            $model->order_id = (int)$datum['order_id'];
            $model->order_item_id = (int)$datum['supplier_order_id'];
            $model->project_name = (string)$datum['project_name'];
            $model->user_id = 1;
            $model->created_at = (string)date("Y-m-d H:i:s", time());
            if (!$model->save()) {
                return $this->error($model->getErrors());
            }
        }

        $orderData = $this->array_group_by($data, 'order_id');
        $modelTaxService = new TaxService();
        foreach ($orderData as $orderId => $items) {
            $modelTax = clone $modelTaxService;
            $modelTax->name = $numberName;
            $modelTax->order_id = $orderId;
            $modelTax->status = 0;
            $modelTax->product_ids = implode(",", array_column($items, 'id'));
            $modelTax->product_names = implode(",", array_column($items, 'brand'));
            $modelTax->created_at = date("Y-m-d H:i:s", time());
            $modelTax->update_at = date("Y-m-d H:i:s", time());
            if (!$modelTax->save()) {
                return $this->error($modelTax->getErrors());
            }
        }

        return $this->success();
    }

    public function array_group_by($arr, $key)
    {
        $grouped = [];
        foreach ($arr as $value) {
            $grouped[$value[$key]][] = $value;
        }
        // Recursively build a nested grouping if more parameters are supplied
        // Each grouped array value is grouped according to the next sequential key
        if (func_num_args() > 2) {
            $args = func_get_args();
            foreach ($grouped as $key => $value) {
                $parms = array_merge([$value], array_slice($args, 2, func_num_args()));
                $grouped[$key] = call_user_func_array('array_group_by', $parms);
            }
        }
        return $grouped;
    }

    /**
     * 已提货页面
     */
    public function actionDelivery()
    {
        return $this->render('delivery');
    }

    /**
     * 已提货页面数据
     */
    public function actionDeliveryList()
    {
        $model = Delivery::find()->groupBy(['name'])->asArray()->all();
        foreach ($model as &$item) {
            $ids = Delivery::find()->where(['name' => $item['name']])->asArray()->all();
            $item['product_ids'] = implode(",", array_column($ids, 'product_ids'));
        }
        $data = [
            'code'  => 0,
            'count' => count($model),
            'data'  => $model
        ];
        return json_encode($data);
    }

    /**
     * 已提货页面产品数据
     */
    public function actionDeliveryItems()
    {
        $ids = \Yii::$app->request->get('ids', null);
        $ids = explode(',', $ids);
        $productItems = (new \yii\db\Query())
            ->select('a.*, c.project_name')
            ->from('supplier_order_item AS a')
            ->leftJoin('supplier_order AS u','a.supplier_order_id = u.id')
            ->leftJoin('order AS c','u.order_id = c.id')
            ->where(['a.id' => $ids])
            ->orderBy('a.id DESC')->all();

        $data = [
            'code'  => 0,
            'count' => count($productItems),
            'data'  => $productItems
        ];
        return json_encode($data);
    }

    /**
     * 创建服务费申请
     */
    public function actionCreateSupprot()
    {
        $id = \Yii::$app->request->post('id');
        $notice = \Yii::$app->request->post('supprot_notice');
        $amount = \Yii::$app->request->post('service_amount');

        if (empty($notice)) $notice = 0;
        if (empty($amount)) $amount = 0;

        $model = new Supprot();
        $model->order_id = $id;
        $model->charge_amount = $amount;
        $model->is_charge = (int)$notice;
        $model->created_at = (string)date("Y-m-d H:i:s", time());
        if (!$model->save()) {
            return $this->error($model->getErrors());
        }
        return $this->success();
    }

    /**
     * 创建服务费申请
     */
    public function actionSupprotList()
    {
        $id = \Yii::$app->request->get('id');
        if (empty($notice)) $notice = 0;
        if (empty($amount)) $amount = 0;

        $model = Supprot::find()
            ->where(['order_id' => $id])
            ->asArray()->all();
        \Yii::$app->response->format = 'json';
        $data = [
            'code'  => 0,
            'count' => count($model),
            'data'  => $model
        ];
        return $data;
    }

    public function actionGoodsList()
    {
        $id = \Yii::$app->request->get('id');
        $model = TaxService::find()
            ->where(['order_id' => $id])
            ->asArray()->all();
        \Yii::$app->response->format = 'json';

        foreach ($model as &$item) {
            $item['status_name']  = ItemStatus::getType($item['status']);
        }

        $data = [
            'code'  => 0,
            'count' => count($model),
            'data'  => $model
        ];
        return $data;
    }

    public function actionUpdateTaxService()
    {
        $id =  \Yii::$app->request->get('id');
        $price =  \Yii::$app->request->get('price');
        $field =  \Yii::$app->request->get('field');
        $status =  \Yii::$app->request->get('status');
        $desc =  \Yii::$app->request->get('status');

        if ($field == 'desc') {
            $model = TaxService::findOne(['id' => $id]);
            $model->$field = $price;
            $model->desc = $price;  //value值都传到price里了。
            if (!$model->save()) {
                return $this->error(300, $model->getErrors());
            }
            return $this->success();
        } else {
            if ($status == 0 && $field != "wait_tax_amount") {
                return $this->error(201, "当前状态，不能操作其他金额字段");
            }
            if ($status == 1 || $status == 4 || $status == 6) {
                return $this->error(201, "当前状态，不能修改任何数据");
            }
            if ($status == 2 && $field != "confirm_tax_amount") {
                return $this->error(201, "当前状态，不能操作其他金额字段");
            }
            if ($status == 3 && $field != "wait_support_amount") {
                return $this->error(201, "当前状态，不能操作其他金额字段");
            }
            if ($status == 5 && $field != "confirm_supprot_amount") {
                return $this->error(201, "当前状态，不能操作其他金额字段");
            }
            $model = TaxService::findOne(['id' => $id]);
            $model->$field = $price;
            if (!$model->save()) {
                return $this->error(300, $model->getErrors());
            }
            return $this->success();
        }
    }

    public function actionUpdateTax()
    {
        $id = \Yii::$app->request->post('id');
        $status = \Yii::$app->request->post('status');
        $price = \Yii::$app->request->post('price');
        $model = TaxService::findOne(['id' => $id]);
        if (empty($model)) return $this->error(300, "该数据已被删除，无法执行任何操作");

        if (in_array($status, [3,4,6]) && empty($price)) {
            return $this->error(300, "对应的金额不能为空");
        }

        $model->status = (int)$status;
        $model->update_at = (string)date("Y-m-d H:i:s");
        if (!$model->save()) {
            return $this->error(300, $model->getErrors());
        }
        return $this->success();

    }

    // 采购商我的订单
    public function actionMyorder()
    {
        return $this->render('myorder');
    }

    // 财务我的订单
    public function actionMyorderCai()
    {
        return $this->render('myorderCai');
    }

    // 设计师我的订单
    public function actionMyorderShe()
    {
        return $this->render('myorderShe');
    }

    /**
     * 采购商我的订单
     * @return false|string
     * @throws \Exception
     */
    public function actionMyorderList()
    {
        $userId = yii::$app->user->identity->id;
        //$userId = 49;
        $strategy = Substance::getInstance($this->strategy);
        // 获取查询参数
        $search = $strategy->getRequest(); // 处理查询参数
        $search['field'] = $search['field'] ? $search['field'] : $this->sort;
        $search['orderBy'] = [$search['field'] => $search['sort'] == 'asc' ? SORT_ASC : SORT_DESC];
        $search['limit'] = $_GET['limit'];
        $search['offset'] = ($_GET['page'] - 1) * 10;
        if ($userId == 1) {
            $search['where'] = [];
        } else {
            $search['where'] = ['order.user' => $userId];
        }

        // 查询数据
        $query = $this->getQuery($search['where'])->leftJoin(
            'admin u',
            "u.id = order.user"
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

        $orderStatusArr = OrderStatus::get();
        if (!empty($array)) {
            foreach ($array as $k => $item) {
                $array[$k]['order_status'] = $orderStatusArr[$item['order_status']];
            }
        }

        $data['code'] = 0;
        $data['count'] = $total;
        $data['data'] = $array;
        return json_encode($data);

    }

    /**
     * 财务我的订单
     * @return false|string
     * @throws \Exception
     */
    public function actionMyorderListCai()
    {
        //$userId = yii::$app->user->identity->id;

        $strategy = Substance::getInstance($this->strategy);
        // 获取查询参数
        $search = $strategy->getRequest(); // 处理查询参数
        $search['field'] = $search['field'] ? $search['field'] : $this->sort;
        $search['orderBy'] = [$search['field'] => $search['sort'] == 'asc' ? SORT_ASC : SORT_DESC];
        $search['limit'] = $_GET['limit'];
        $search['offset'] = ($_GET['page'] - 1) * 10;
        $search['where'] = [];

        // 查询数据
        $query = $this->getQuery($search['where'])->leftJoin(
            'admin u',
            "u.id = order.user"
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

        $orderStatusArr = OrderStatus::get();
        if (!empty($array)) {
            foreach ($array as $k => $item) {
                $array[$k]['order_status'] = $orderStatusArr[$item['order_status']];
            }
        }

        $data['code'] = 0;
        $data['count'] = $total;
        $data['data'] = $array;
        return json_encode($data);

    }

    /**
     * 设计师我的订单
     * @return false|string
     * @throws \Exception
     */
    public function actionMyorderListShe()
    {
        $userId = yii::$app->user->identity->id;
//        $userId = 58;
        $strategy = Substance::getInstance($this->strategy);
        // 获取查询参数
        $search = $strategy->getRequest(); // 处理查询参数
        $search['field'] = $search['field'] ? $search['field'] : $this->sort;
        $search['orderBy'] = [$search['field'] => $search['sort'] == 'asc' ? SORT_ASC : SORT_DESC];
        $search['limit'] = $_GET['limit'];
        $search['offset'] = ($_GET['page'] - 1) * 10;
        $search['where'] = ['u.designer' => $userId];
        // 查询数据
        $query = $this->getQuery($search['where'])->leftJoin(
            'admin u',
            "u.id = order.user"
        );
        // 查询数据条数
        $total = $query->count();

        if ($total) {
            $columns = ['order.*','u.username as owner','u.designer as designer'];
            $array = $query->select($columns)->offset($search['offset'])->limit($search['limit'])->orderBy($search['orderBy'])->all();
            if ($array) $this->afterSearch($array);
        } else {
            $array = [];
        }

        $orderStatusArr = OrderStatus::get();
        if (!empty($array)) {
            foreach ($array as $k => $item) {
                $array[$k]['order_status'] = $orderStatusArr[$item['order_status']];
            }
        }

        $data['code'] = 0;
        $data['count'] = $total;
        $data['data'] = $array;
        return json_encode($data);

    }

    /**
     * 填写物流运输信息
     */
    public function actionTransportUpdate()
    {
        if (empty($_POST['transport']) || empty($_POST['time']) || empty($_POST['image']) || empty($_POST['att']) ) {
            return $this->error(201,"资料填写不全，请填写完全后提交");
        }
        $id = $_POST['orderId'];
        $model = Delivery::findOne(['id' => $id]);
        $model->transport = $_POST['transport'];
        $model->port_time = $_POST['time'];
        $model->image = $_POST['image'];
        $model->file = $_POST['att'];

        if (!$model->save()) return $this->error(503, $model->errors());
        return $this->success();
    }

}
