<?php

namespace backend\controllers;

use backend\models\Order;
use backend\models\System;
use yii\db\Exception;

/**
 * Class SystemController 系统配置 执行操作控制器
 * @package backend\controllers
 */
class CreateController extends Controller
{

    /**
     * @var string 定义使用的model
     */
    public $modelClass = 'backend\models\System';
     
    /**
     * 查询处理
     * @param  array $params
     * @return array 返回数组
     */
    public function where($params)
    {
        return [
            
        ];
    }

    public function actionIndex()
    {
        $data = System::find()->where(['id'=>1])->asArray()->one();
        return $this->render('index', $data);
    }


    public function actionFrom(){

        print_r($_POST);
        print_r($_FILES);
        die;


        //数据验证
        if (empty($_POST['phone']) || empty($_POST['address']) || empty($_POST['currency']))
        {
            return 0;
        }


        try{
            $number = time().rand(0,10000);
            $order = [
                'order_number' => $number,
                'user' =>  $_POST['user'],
                'date' => $_POST['date'],
                'order_status' => 1,
                'name' =>  $_POST['name'],
                'phone' =>  $_POST['phone'],
                'address' => $_POST['address'],
                'currency' => $_POST['currency'],
                'create_time'=> date("Y:m:d H:i:s",time()),
            ];
            \Yii::$app->db->createCommand()->insert(Order::tableName(),$order)->execute();
            $createId = \Yii::$app->db->getLastInsertID();
            $data = $_POST['data'];
            $orderItem = [];
            $columns = ['order_id','order_number','brand','number','type','desc','create_time'];

            try{
                if (!empty($data)){
                    foreach ($data as $key=>$value){
                        $orderItem[] = [
                            'order_id' => $createId,
                            'order_number' => $number,
                            'brand' =>  $value['brand'],
                            'number' => $value['number'],
                            'type' =>  $value['type'],
                            'desc' =>  $value['desc'],
                            'create_time'=> date("Y:m:d H:i:s",time()),
                        ];
                    }
                    \Yii::$app->db->createCommand()->batchInsert(Order::tableItemName(), $columns, $orderItem)->execute();
                }
            }catch (Exception $exception){
                Order::deleteAll(['id'=>$createId]);
                return 0;
            }
            return 1;
        }catch (Exception $exception){
            return 0;
        }





    }
}
