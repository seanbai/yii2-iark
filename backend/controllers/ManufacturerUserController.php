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
                $user[$key]['status'] = '1';
            }else{
                $user[$key]['status'] = '0';
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
        if (isset($_GET['id'])){
            $model = Admin::findOne(['id'=>$_GET['id']]);
            $model->status = 20;
            if ($model->save()){
                return $this->success(0);
            }else{
                $error = array_values($model->getErrors());
                return $this->error(400, $error[0][0]);
            }
        }else{
            $id = $_POST['id'];
            $model = Admin::findOne(['id'=>$id]);
            $model->name = $_POST['name'];
            $model->contact = $_POST['contact'];
            $model->phone = $_POST['phone'];
            $model->email = $_POST['email'];
            $model->address = $_POST['address'];
            if ($model->save()){
                return $this->success(0);
            }else{
                $error = array_values($model->getErrors());
                return $this->error(400, $error[0][0]);
            }
        }
    }


}