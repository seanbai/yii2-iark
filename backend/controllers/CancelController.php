<?php
/**
 * Created by PhpStorm.
 * User: silk
 * Date: 20-5-5
 * Time: 下午6:35
 */


namespace backend\controllers;


use backend\models\Order;
use backend\models\OrderItem;


class CancelController extends Controller
{


    public function actionList()
    {
        $strategy = Substance::getInstance($this->strategy);
        // 获取查询参数
        $search = $strategy->getRequest(); // 处理查询参数
        $search['field'] = $search['field'] ? $search['field'] : $this->sort;
        $search['orderBy'] = [$search['field'] => $search['sort'] == 'asc' ? SORT_ASC : SORT_DESC];

        if (yii::$app->user->identity->id != 1) {
            $search['where'] = ['user'=> yii::$app->user->identity->id];
        }

        $search['where'] = ['<>', 'order_status', 0];

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

        foreach ($array as $key=>$value){
            if (empty($value['product_amount'])) $array[$key]['product_amount'] = '';
            if (empty($value['tax'])) $array[$key]['tax'] = '';
        }
        $data['data'] = $array;

        return json_encode($data);
    }

}