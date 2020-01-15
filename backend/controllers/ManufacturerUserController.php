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


        $data['code'] = 0;
        $data['count'] = count($user);

        foreach ($user as $key=>$value){
            if ($value['status'] == '20'){
                $user[$key]['status'] = '禁用';
            }else{
                $user[$key]['status'] = '有效';
            }
        }
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
        $id = $_POST['id'];
        $model = Admin::findOne(['id'=>$id]);
        $model->status = 20;

        if ($model->save()){
            return 0;
        }else{
            return 400;
        }



    }


}