<?php

namespace backend\controllers;

use backend\helpers\OrderStatus;
use backend\models\Admin;
use backend\models\Auth;
use backend\models\Order;
use backend\models\OrderItem;
use backend\models\SupplierOrder;
use backend\models\SupplierOrderItem;
use common\helpers\Helper;
use common\strategy\Substance;
use Yii;
use yii\db\Query;
use yii\web\Response;

/**
 * Class OrderController My Order 执行操作控制器
 * @package backend\controllers
 */
class ManufacturerController extends Controller
{
    /**
     * @var string 定义使用的model
     */
    public $modelClass = 'backend\models\Order';

    /**
     * 查询处理
     * @param array $params
     * @return array 返回数组
     */
    public function where($params)
    {
        return [
            'user' => "=",
        ];
    }

    public function actionSearch()
    {
        // 实例化数据显示类
        /* @var $strategy \common\strategy\Strategy */
        $strategy = Substance::getInstance($this->strategy);
        // 获取查询参数
        $search            = $strategy->getRequest(); // 处理查询参数
        $search['field']   = $search['field'] ? $search['field'] : $this->sort;
        $search['orderBy'] = [$search['field'] => $search['sort'] == 'asc' ? SORT_ASC : SORT_DESC];

        if (yii::$app->user->identity->id == 1) {
            $search['where'] = Helper::handleWhere($search['params'], $this->where($search['params']));
        } else {
            $search['where'] = ['user' => yii::$app->user->identity->id];
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

        return $this->success($strategy->handleResponse($array, $total));
    }

    public function actionIndex()
    {
        $data = [
            'user'   => Admin::getUser(),
            'status' => Order::status(),
            'pay'    => Order::pay(),
        ];

        return $this->render('quoteation', $data);
    }


    public function actionList()
    {
        $strategy = Substance::getInstance($this->strategy);
        // 获取查询参数
        $search            = $strategy->getRequest(); // 处理查询参数
        $search['field']   = $search['field'] ? $search['field'] : $this->sort;
        $search['orderBy'] = [$search['field'] => $search['sort'] == 'asc' ? SORT_ASC : SORT_DESC];
        $query = (new Query())->from(SupplierOrder::tableName());

        if (yii::$app->user->identity->id == 1) {
            $search['where'] = Helper::handleWhere($search['params'], $this->where($search['params']));
            // 查询数据
            //$query = $this->getQuery($search['where']);
            $query->where($search['where']);
        } else {
            //供应商订单信息
            $search['where'] = ['supplier_id' => yii::$app->user->identity->id];
            // 查询数据
            $query->where($search['where']);
        }
        //只出现子订单为等待报价的
        $query->andWhere(['order_status'=>31]);

        if (YII_DEBUG) $this->arrJson['other'] = $query->createCommand()->getRawSql();

        // 查询数据条数
        $total = $query->count();
        if ($total) {
            $status = OrderStatus::getChild();
            $array = $query->offset($search['offset'])->limit($search['limit'])->orderBy($search['orderBy'])->all();
            if ($array) $this->afterSearch($array);
            array_walk($array,function (&$value) use ($status){
                $value['order_status'] =  $status[31];
            });
            $data['code'] = 0;
        } else {
            $array        = [];
            $data['code'] = 0;
        }

        $data['count'] = $total;
        $data['data']  = $array;
        return json_encode($data);
    }

    //供应商订单详情
    public function actionItems()
    {
        $supplierOrderId = \Yii::$app->request->get('id');
        $supplierOrder = SupplierOrder::findOne($supplierOrderId);
        $items = $supplierOrder->hasMany(SupplierOrderItem::class,['supplier_order_id' => 'id'])
                ->asArray()->all();
        $data = [
            'code' => 0,
            'msg' => '',
            'count' => count($items),
            'data' => $items
        ];
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return$data;
    }


    /**
     * 产品报价
     *
     * @acl manufacturer/quote-item
     * @return array
     */
    public function actionQuoteItem()
    {
        $itemId = \Yii::$app->request->get('id');
        $value = \Yii::$app->request->get('price');


        if(!$itemId || !$value){
            $data = [
                'code' => 400,
                'msg' => 'invalid input params'
            ];
        }else{
            $supplierOrderItem = SupplierOrderItem::findOne($itemId);
            if(!$supplierOrderItem){
                $data = [
                    'code' => 400,
                    'msg' => 'the product info is invalid'
                ];
            }else{
                $order = SupplierOrder::findOne($supplierOrderItem->supplier_order_id);
                if (!$order || ($order->quote_status == 1)) {
                    $data = [
                        'code' => 400,
                        'msg' => 'The platform has been quoted, please submit directly.'
                    ];
                }else{
                    $user = Admin::findOne($order->supplier_id);
                    $rate = (float) ($user->discount / 100);
                    $rate = (1 - $rate);
                    $price = $value * $rate;
                    $supplierOrderItem->setAttribute('price', $price); //折扣后价格
                    $supplierOrderItem->setAttribute('origin_price', $value); //原价
                    $supplierOrderItem->save();
                    //保存单个产品的报价到order item
                    $orderItem = OrderItem::findOne($supplierOrderItem->order_item_id);
                    $orderItem->setAttribute('price',$price);
                    $orderItem->setAttribute('origin_price', $value);
                    $orderItem->save();
                    $data = [
                        'code' => 200,
                    ];
                }
            }
        }
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $data;
    }


    //生产中订单列表
    public function actionProduction()
    {
        if(\Yii::$app->request->isAjax){
            $userId = Yii::$app->user->id;
            $status = 91;
            $query = SupplierOrder::find();
            if(!$this->isAdministrator()){
                $where = ['supplier_id'=>$userId, 'order_status'=>$status];
            }else{
                $where = ['order_status'=>$status];
            }
            $orders = $query->where($where)
                ->asArray()->all();
            $data = [
                'code' => 0,
                'msg' => '',
                'count' => count($orders),
                'data' => $orders
            ];
            \Yii::$app->response->format = Response::FORMAT_JSON;
            return$data;
        }
        return $this->render('production');
    }


    /****
     * 产品列表页面
     * @return false|string
     */
    public function actionProducts()
    {
        $orderId = $_GET['orderId'];

        //$model = OrderItem::find()->where(['order_id'=>$orderId])->asArray()->all();
        $model = SupplierOrderItem::find()->where(['supplier_order_id'=>$orderId])->asArray()->all();
        return $this->render('products', [
            'products' => $model,
        ]);
    }


    public function actionStatus()
    {
        $id = $_GET['id'];
        $model = Order::findOne(['id'=>$id]);

        return $this->render('view', [
            'id' => $id,
            'model'=>$model
        ]);
    }

    public function actionUpdate()
    {
        //todo 供货商状态更改接口，需要改造，$_POST = ['status' =?, 'id' => ]
        $data = $_POST;
        $model = Order::findOne(['id'=>$data['id']]);
        if ($data['status'] == 10 && $data['prepayment'] == 1){
            $model->order_status = 11;    //供货商收到定金
        }else{
            $model->order_status = 23;    //供货商未收到定金
        }
        if ($data['status'] == 11){
            $model->order_status = 12;    //支付订金完成
        }
        if ($data['status'] == 12){
            $model->order_status = 13;    //尾款申请
        }
        if ($data['status'] == 13){
            $model->order_status = 14;    //支付订金完成
        }


        if ($model->save()){
            return $this->success();
        }else{
            return $this->error(400, Helper::arrayToString($model->getErrors()));
        }



    }


    /**
     * 提价订单报价
     *
     * @acl manufacturer/submit-quote
     * @return array
     */
    public function actionSubmitQuote()
    {
        $supplierOrderId = \Yii::$app->request->post('id');
        $supplierOrder = SupplierOrder::findOne($supplierOrderId);
        try{
            $supplierOrder->quote();
            $msg = "The order has been submit quote.";
            $code = 200;
        }catch (\Exception $exception){
            $code = 400;
            $msg = "The order submit quote failed.";
        }
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'code' => $code,
            'msg' => $msg
        ];
    }


    /**
     * 订单完成生产
     *
     * @acl manufacturer/complete-product
     * @return array
     */
    public function actionCompleteProduction()
    {
        $supplierOrderId = \Yii::$app->request->post('id');
        $supplierOrder = SupplierOrder::findOne($supplierOrderId);
        try{
            $supplierOrder->setStatus(101);
            $msg = '';
            $code = 200;
        }catch (\Exception $exception){
            $code = 400;
            $msg = $exception->getMessage();
        }
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'code' => $code,
            'msg' => $msg
        ];
    }

    public function actionPendingOrder()
    {
        return $this->render('pendingorder');
    }

    /**
     * @acl manufacturer/order-update
     * @return array
     */
    public function actionOrderUpdate()
    {
        $post = \Yii::$app->request->post();
        $id = $post['id'];
        $status = $post['status'];
        $childOrder = SupplierOrder::findOne($id);
        if($childOrder){
            $code = 0;
            $msg = '';
            try{
                $order = Order::findOne($childOrder->order_id);
                if ($status == 91 && $order->order_status != 9) {
                    $order->order_status = 9;//主订单变为生产中
                    $order->save();
                }
                $childOrder->order_status = $status;
                $childOrder->save(false);
                //如果所有子订单变成生产完成，则修改父订单状态为生产完成，如果主订单进入了尾款，则所有子订单立即进入尾款状态
                if ($status == 101 && $order->order_status == 9) {
                    $allChildOrders = $order->getChildOrders();
                    $update = true;
                    foreach ($allChildOrders as $child) {
                        if ($child['order_status'] != 101) {
                            $update = false;break;
                        }
                    }
                    if ($update) {
                        $order->order_status = 10;
                        $order->save();
                    }
                }

                if ($status == 141 && $order->order_status == 13) {
                    $allChildOrders = $order->getChildOrders();
                    $update = true;
                    foreach ($allChildOrders as $child) {
                        if ($child['order_status'] != 141) {
                            $update = false;break;
                        }
                    }
                    if ($update) {
                        $order->order_status = 14;
                        $order->save();
                    }
                }

            }catch (\Exception $exception){
                $code = 400;
                $msg  = 'The request error';
            }
        }else{
            $code = 400;
            $msg  = 'The request error';
        }
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'code' => $code,
            'msg' => $msg
        ];
    }

    public function actionPendingOrderList()
    {
        //  81 => '定金待确认',//当主订单变为8定金已收取的同时，将所有子订单状态变为定金待确认
        //  91 => '生产中',//当子订单确认收到了定金后，便更改子订单状态为生产中，当第一个子订单状态变为生产中，
        //          则主订单状态变为生产中
        //  101 => '生产完成',//子订单状态一定是从生产中变为生产完成，完成后才能去确认收取尾款
        $orderStatus = [81, 91 , 101,131];

        //用户过滤
        $userId = \Yii::$app->user->id;

        $query = SupplierOrder::find()->select('*')
            ->where(['in', 'order_status', $orderStatus]);
        if(!$this->isAdministrator()){
            $query->andWhere('supplier_id = :supplier_id',[':supplier_id' => $userId]);
        }
        $total = $query->count('id');
        $limit = $_GET['limit'];
        $offset = ($_GET['page'] - 1) * 10;
        $query->orderBy('id desc')->limit($limit)->offset($offset);
        $orders = $query->asArray()->all();
        $orderStatusArr = OrderStatus::getChild();
        if (!empty($orders)) {
            foreach ($orders as $k => $order) {
                $orders[$k]['order_status_label'] = $orderStatusArr[$order['order_status']];
            }
        }

        $data = [
            'code' => 0,
            'msg' => '',
            'count' => $total,
            'data' => $orders
        ];
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $data;
    }

    /**
     * @return bool
     */
    private function isAdministrator()
    {
        $role = \Yii::$app->user->id;
        return $role == 1;
    }
}
