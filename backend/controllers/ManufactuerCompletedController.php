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
        $query = SupplierOrder::find()->select('supplier_order.*,o.name,o.package,o.project_name,o.address');
        if(!$this->isAdministrator()){
            $query->andWhere('supplier_id = :supplier_id', [':supplier_id' => $userId]);
        }
        $query->leftJoin('order o', 'o.id = supplier_order.order_id');
       try{
           $total = $query->count('supplier_order.id');
           $limit = $_GET['limit'];
           $offset = ($_GET['page'] - 1) * 10;
           $query->orderBy('supplier_order.id desc')->limit($limit)->offset($offset);
           $orders = $query->asArray()->all();
       }catch (\Exception $exception){
            echo $exception->getMessage();die;
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
}