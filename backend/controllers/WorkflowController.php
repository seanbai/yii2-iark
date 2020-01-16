<?php

namespace backend\controllers;

use backend\models\Admin;
use backend\models\Auth;
use backend\models\Create;
use backend\models\Order;
use backend\models\OrderItem;
use backend\models\ProductPushRecord;
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
        if (empty($data['pPrice'])){
            $status = 2;
            $model->order_status = $status;
        } else {
            $model->order_status = 5;
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
        $model = Order::findOne(['id'=>$data['id']]);
        $model->order_status = $data['status'];

        if ($model->save()){
            return $this->success('保存成功');
        } else {
            return $this->error(300, Helper::arrayToString($model->getErrors()));
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
