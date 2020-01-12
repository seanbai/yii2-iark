<?php

namespace backend\controllers;

use backend\models\Order;

class PurchaserCompletedController extends Controller
{

    public function actionIndex()
    {
        $data = [];
        return $this->render('index',$data);
    }


    public function actionEnd()
    {
        $data = Order::find()->where(['order_status'=>13])->asArray()->all();

        $model['code'] = 0;
        $model['count'] = count($data);
        $model['data'] = $data;

        return json_encode($model);


    }

}