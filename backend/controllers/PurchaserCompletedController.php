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
        $query = Order::find()->where(['order_status'=>200]);

        $userId = \Yii::$app->user->identity->id;
        if ($userId != 1)
        {
            $query->andWhere(['user' => $userId]);
        }

        $count = $query->count('*');
        $limit = $_GET['limit'];
        $offset = ($_GET['page'] - 1) * 10;
        $query->limit($limit)->offset($offset);
        $data = $query->asArray()->all();

        $model['code'] = 0;
        $model['count'] = $count;
        $model['data'] = $data;

        return json_encode($model);
    }

}