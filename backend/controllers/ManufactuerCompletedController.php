<?php

namespace backend\controllers;

use backend\helpers\OrderStatus;
use backend\models\SupplierOrder;
use yii\web\Response;

class ManufactuerCompletedController extends Controller
{

    public function actionIndex()
    {
        $data = [];
        return $this->render('index',$data);
    }

    /**
     * @return bool
     */
    private function isAdministrator()
    {
        $role = \Yii::$app->user->id;
        return $role == 1;
    }

    public function actionList()
    {
        //用户过滤
        $userId = \Yii::$app->user->id;
        $query = SupplierOrder::find()->select('*')
            ->where('order_status = :order_status', [':order_status' => 101]);
        if(!$this->isAdministrator()){
            $query->where('supplier_id = :supplier_id', [':supplier_id' => $userId]);
        }

        $total = $query->count('id');
        $limit = $_GET['limit'];
        $offset = ($_GET['page'] - 1) * 10;
        $query->orderBy('id desc')->limit($limit)->offset($offset);
        $orders = $query->asArray()->all();

        $data = [
            'code' => 0,
            'msg' => '',
            'count' => $total,
            'data' => $orders
        ];
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $data;
    }
}