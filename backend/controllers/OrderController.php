<?php

namespace backend\controllers;

use backend\helpers\OrderStatus;
use backend\models\Admin;
use backend\models\Auth;
use backend\models\Order;
use backend\models\OrderItem;
use common\helpers\Helper;
use common\strategy\Substance;
use Yii;

use backend\models\Create;
use yii\db\Expression;
use yii\web\Response;


/**
 * Class OrderController My Order 执行操作控制器
 * @package backend\controllers
 */
class OrderController extends Controller
{
    /**
     * @var string 定义使用的model
     */
    public $modelClass = 'backend\models\Order';

    /**
     * 查询处理
     *
     * @param  array $params
     *
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
        $limit = $_GET['limit'];
        $page = $_GET['page'];

        // 实例化数据显示类
        /* @var $strategy \common\strategy\Strategy */
        $strategy = Substance::getInstance($this->strategy);
        // 获取查询参数
        $search = $strategy->getRequest(); // 处理查询参数
        $search['field'] = $search['field'] ? $search['field'] : $this->sort;
        $search['orderBy'] = [$search['field'] => $search['sort'] == 'asc' ? SORT_ASC : SORT_DESC];
        $search['where'] = ['<>', 'order_status', 0];
        $search['offset'] = ($page - 1) * $limit;
        $search['limit'] = $limit;

        if (yii::$app->user->identity->id != 1){
            $search['andWhere'] = ['user' => yii::$app->user->identity->id];
        }

        // 查询数据
        $query = $this->getQuery($search['where']);
        if (YII_DEBUG) $this->arrJson['other'] = $query->createCommand()->getRawSql();

        // 查询数据条数
        $total = $query->count();
        if ($total){
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
            'user' => Admin::getUser(),
            'status' => Order::status(),
            'pay' => Order::pay(),
        ];

        return $this->render('index', $data);
    }


    public function actionList()
    {
        $limit = $_GET['limit'];
        $page = $_GET['page'];

        $strategy = Substance::getInstance($this->strategy);
        // 获取查询参数
        $search = $strategy->getRequest(); // 处理查询参数
        $search['field'] = $search['field'] ? $search['field'] : $this->sort;
        $search['orderBy'] = [$search['field'] => $search['sort'] == 'asc' ? SORT_ASC : SORT_DESC];
        $search['where'] = ['<>', 'order_status', 401];
        $search['offset'] = ($page - 1) * $limit;
        $search['limit'] = $limit;

        if (yii::$app->user->identity->id != 1){
            $search['andWhere'] = ['user' => yii::$app->user->identity->id];
        }

        // 查询数据
        $query = $this->getQuery($search['where']);
        if (YII_DEBUG) $this->arrJson['other'] = $query->createCommand()->getRawSql();

        // 查询数据条数
        $total = $query->count();
        if ($total){
            $array = $query->offset($search['offset'])->limit($search['limit'])->orderBy($search['orderBy'])->all();
            if ($array) $this->afterSearch($array);
        } else {
            $array = [];
        }
        if (!empty($array)) {
            foreach ($array as $key => $value) {
                if (empty($value['product_amount'])) $array[$key]['product_amount'] = '';
                if (empty($value['tax'])) $array[$key]['tax'] = '';
            }
        }
        array_walk($array, function (&$value){
            //订单状态输出
            $value['status_label'] = OrderStatus::get($value['order_status']);
        });
        $data['code'] = 0;
        $data['count'] = $total;
        $data['data'] = $array;

        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $data;
    }

    /****
     * 产品列表页面
     * @return array
     */
    public function actionProducts()
    {
        $orderId = $_GET['id'];

        $model = OrderItem::find()->where(['order_id' => $orderId])->asArray()->all();
        Yii::$app->response->format = 'json';
        return [
            'code' => 0,
            'count' => count($model),
            'data' => $model,
        ];
       /* return $this->render(
            'products', [
            'products' => $model,
        ]
        );*/
    }


    public function actionStatus()
    {
        $id = $_GET['id'];
        $model = Order::findOne(['id' => $id]);

        return $this->render(
            'view', [
            'id' => $id,
            'status' => $model['order_status']
        ]
        );
    }

    public function actionUpdate()
    {
        $data = $_POST;
        $model = Order::findOne(['id' => $data['id']]);
        /*if ($data['status'] == 10 && $data['quote'] == 1){
            $model->order_status = 6;   //确定报价
        } else {
            $model->order_status = 23;   //拒绝报价
        }
        if ($data['status'] == 6){
            $model->order_status = 7;   //支付订金完成
        }
        if ($data['status'] == 13){
            $model->order_status = 14;   //尾款支付完成
        }*/
        $status = $data['status']; // 5, 确认报价
        $model->order_status = $status;


        if ($model->save()){
            return $this->success();
        } else {
            return $this->error(400, Helper::arrayToString($model->getErrors()));
        }
    }


    /**
     * 订单产品信息
     *
     * @acl order/items
     */
    public function actionItems()
    {
        $orderId = \Yii::$app->request->get('id', null);
        try{
            $order = Order::findOne($orderId);
            if(!$order){
                return $this->error(400, 'The order does not exist');
            }
            $items = $order->items;
        }catch (\Exception $exception){
            return $this->error(400, $exception->getMessage());
        }
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'code' => 0,
            'count' => count($items),
            'data'  => $items
        ];
    }

    /**
     * 取消采购
     * @acl  order/cancel
     * @return mixed|string
     */
    public function actionCancel()
    {
        $id = $_POST['id'];
        $model = Order::findOne(['id' => $id]);
        if($model->order_status >= 5){
            return $this->error(400,'The order has been submit quote, can not cancel.');
        }
        $model->order_status = 401;   //必须在确认报价前
        if ($model->save()){
            return $this->success();
        } else {
            return $this->error(400, Helper::arrayToString($model->getErrors()));
        }
    }


    public function actionPurchaserCancel()
    {

        $data = [
            'user' => Admin::getUser(),
            'status' => Order::status(),
            'pay' => Order::pay(),
        ];

        return $this->render('index2', $data);
    }


    public function actionPurchaserCancelList()
    {
        $userId = yii::$app->user->identity->id;

        $model = Order::find()->where(['order_status' => 401]);
        if ($userId != 1)
        {
            $model->andWhere(['user' => $userId]);
        }

        $total = $model->count();
        if ($total){
            $array = $model->asArray()->all();
        } else {
            $array = [];
        }

        $data['code'] = 0;
        $data['count'] = $total;

        foreach ($array as $key => $value) {
            if (empty($value['product_amount'])) $array[$key]['product_amount'] = '';
            if (empty($value['tax'])) $array[$key]['tax'] = '';
        }
        $data['data'] = $array;

        return json_encode($data);
    }

    /**
     * @acl： order/watingquote
     * @return string
     */
    public function actionWatingquote()
    {
        return $this->render('watingquote');
    }

    /**
     * @acl： order/watingquote-list
     * @return array
     */
    public function actionWatingquoteList()
    {
        $orderStatus = [4,5,6,11,14]; //todo 更改为报价中的状态列表
        \Yii::$app->response->format = Response::FORMAT_JSON;
        if(!$orderStatus){
            $total = 0;
            $orders = [];
        }else{
            try{
                $query = Order::find()->select('order.*')
                    ->where(['in', 'order_status', $orderStatus]);
                $total = $query->count('order.id');
                $limit = $_GET['limit'];
                $offset = ($_GET['page'] - 1) * 10;
                $query->orderBy('id desc')->limit($limit)->offset($offset);
                $orders = $query->asArray()->all();
            }catch (\Exception $exception){
                echo $exception->getMessage();die;
            }
        }
        $orderStatusArr = OrderStatus::get();
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

    public function actionOrderPay()
    {
        $msg = 'Operation failed';
        $code = 400;
        $orderId = \Yii::$app->request->post('orderId', null);
        if ($orderId) {
            $deposit =  \Yii::$app->request->post('pay_deposit', null);
            $balance =  \Yii::$app->request->post('pay_balance', null);
            $tax =  \Yii::$app->request->post('pay_tax', null);
            $order = Order::findOne(['id'=>$orderId]);
            if ($order->id) {
                if ($deposit) {
                    //确认支付定金
                    $order->order_status = 7;
                    $msg = 'Operation successed';
                    $code = 200;
                    $order->save();
                } else if ($balance) {
                    //确认支付尾款
                    $order->order_status = 12;
                    $msg = 'Operation successed';
                    $code = 200;
                    $order->save();
                } else if ($tax) {
                    //确认支付税金
                    $order->order_status = 15;
                    $msg = 'Operation successed';
                    $code = 200;
                    $order->save();
                }

            }
        }
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $data = [
            'code'  => $code,
            'msg'   => $msg,
            'data'  => []
        ];
        return $data;
    }
}
