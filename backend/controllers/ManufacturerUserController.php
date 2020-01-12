<?php

namespace backend\controllers;

use backend\models\Admin;
use backend\models\User;

class ManufacturerUserController extends Controller
{

    const TYPE = 'manufacturer';


    public function actionIndex()
    {
        $data = [];
        return $this->render('index',$data);
    }

    //用户信息
    public function actionList()
    {
        $user = Admin::find()->where(['role'=>'manufacturer'])->asArray()->all();

        if (count($user) > 0){
            $data['code'] = 0;
        }else{
            $data['code'] = 0;
        }
        $data['count'] = count($user);
        $data['data'] = $user;

        return json_encode($data);
    }

    public function actionAdd()
    {
        $data = [];
        return $this->render('add',$data);
    }


    public function actionStatus()
    {
        print_r($_POST);


    }


}