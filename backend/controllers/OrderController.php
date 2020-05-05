<?php

namespace backend\controllers;

use backend\models\Admin;
use backend\models\Auth;
use backend\models\Order;
use backend\models\OrderItem;
use common\helpers\Helper;
use common\strategy\Substance;
use Yii;

use backend\models\Create;


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
        // 实例化数据显示类
        /* @var $strategy \common\strategy\Strategy */
        $strategy = Substance::getInstance($this->strategy);
        // 获取查询参数
        $search = $strategy->getRequest(); // 处理查询参数
        $search['field'] = $search['field'] ? $search['field'] : $this->sort;
        $search['orderBy'] = [$search['field'] => $search['sort'] == 'asc' ? SORT_ASC : SORT_DESC];

        if (yii::$app->user->identity->id == 1){
            $search['where'] = Helper::handleWhere($search['params'], $this->where($search['params']));
        } else {
            $search['where'] = ['user' => yii::$app->user->identity->id];
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
        $strategy = Substance::getInstance($this->strategy);
        // 获取查询参数
        $search = $strategy->getRequest(); // 处理查询参数
        $search['field'] = $search['field'] ? $search['field'] : $this->sort;
        $search['orderBy'] = [$search['field'] => $search['sort'] == 'asc' ? SORT_ASC : SORT_DESC];

        if (yii::$app->user->identity->id != 1){
            $search['where'] = ['user' => yii::$app->user->identity->id];
        }

        $search['where'] = ['<>', 'order_status', 0];

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
        $data['code'] = 0;
        $data['count'] = $total;

        foreach ($array as $key => $value) {
            if (empty($value['product_amount'])) $array[$key]['product_amount'] = '';
            if (empty($value['tax'])) $array[$key]['tax'] = '';
        }
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

        $model = OrderItem::find()->where(['order_id' => $orderId])->asArray()->all();
        return $this->render(
            'products', [
            'products' => $model,
        ]
        );
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
        if ($data['status'] == 10 && $data['quote'] == 1){
            $model->order_status = 6;   //确定报价
        } else {
            $model->order_status = 23;   //拒绝报价
        }
        if ($data['status'] == 6){
            $model->order_status = 7;   //支付订金完成
        }
        if ($data['status'] == 13){
            $model->order_status = 14;   //尾款支付完成
        }


        if ($model->save()){
            return $this->success();
        } else {
            return $this->error(400, Helper::arrayToString($model->getErrors()));
        }
    }


    public function actionCancel()
    {
        $id = $_POST['id'];
        $model = Order::findOne(['id' => $id]);
        $model->order_status = 0;   //订单已取消
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

        $model = Order::find()->where(['order_status' => 0]);
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

}
