<?php

namespace backend\controllers;

use backend\models\Admin;

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

        $data = [
            'user' => Admin::getUser(),
        ];

        return $this->render('index', $data);
    }
}
