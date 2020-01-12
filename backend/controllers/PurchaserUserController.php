<?php

namespace backend\controllers;

use backend\models\Admin;

class PurchaserUserController extends Controller
{

    public function actionIndex()
    {
        $data = [];
        return $this->render('index',$data);
    }


    //新增数据页面
    public function actionAdd(){
        $data = [];
        return $this->render('add',$data);
    }

    //状态变更
    public function actionstatus()
    {

    }

    /***
     * 用户列表
     */
    public function actionList()
    {
        $user = Admin::find()->where(['role'=>'buyer'])->asArray()->all();


        $data['code'] = 0;
        $data['count'] = count($user);
        $data['data'] = $user;

        return json_encode($data);
    }

}