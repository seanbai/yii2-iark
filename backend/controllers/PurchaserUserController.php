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

    /***
     * 用户列表
     */
    public function actionList()
    {
        $user = Admin::find()->where(['role'=>'buyer'])->asArray()->all();

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

}