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
     
    /**
     * 查询处理
     * @param  array $params
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


        $search['where'] = Helper::handleWhere($search['params'], $this->where($search['params']));


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
            $data['code'] = 0;
        } else {
            $array = [];
            $data['code'] = 400;
        }

        $data['count'] = $total;

//        foreach ($array as $key=>$val){
//            $array[$key]['order_status'] =  Create::getData($val['order_status']);
//        }

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

        $data['data'] = $model;

        return json_encode($data);
    }


    public function actionStatus()
    {
        try{
            $id = $_POST['id'];
            $type = $_POST['status'];
            $bj = $_POST['bj'];

            if ($type == 1){
                \Yii::$app->db->createCommand()->update(
                    'order_status',
                    [
                        'status' => 1,
                        'update_time'=>date('Y:m:d H:i:s',time())
                    ],
                    [
                        'order_id' => $id,
                        'type' => $type
                    ]
                )->execute();
                \Yii::$app->db->createCommand()->update(
                    'order',
                    [
                        'order_status' => 2,
                    ],
                    [
                        'id'=>$id,
                    ]
                )->execute();
                //bj=1,供货商报价,bj=2,平台报价
                if ($bj == 2){
                    \Yii::$app->db->createCommand()->update(
                        'order_status',
                        [
                            'status' => 2,
                            'money' => $_POST['pPrice'],
                            'update_time'=>date('Y:m:d H:i:s',time())
                        ],
                        [
                            'order_id' => $id,
                            'type' => $type
                        ]
                    )->execute();
                    \Yii::$app->db->createCommand()->update(
                        'order',
                        [
                            'order_status' => 3,
                        ],
                        [
                            'id'=>$id,
                        ]
                    )->execute();
                }
            }elseif ($type == 2){
                //统计总价后保存

            }elseif ($type == 3){

            }





            return $this->success(200,"保存成功");
        }catch (\Exception $exception){
            return $this->error(300,"系统异常,请稍后再试");
        }

    }

    public function actionUpdate()
    {
        $data = [];
        $id = $_GET['id'];
        $model = Order::find()->where(['id'=>$id])->asArray()->one();

        return $this->render('update', [
            'status' => $model['order_status'],
            'id' => $id
        ]);

    }
}
