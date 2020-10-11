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
            if (empty($_POST['designer'])) return $this->error(400, "设计师不能为空");
            $id = $_POST['id'];
            $model = Admin::findOne(['id'=>$id]);
            $model->name = $_POST['name'];
            $model->contact = $_POST['contact'];
            $model->phone = $_POST['phone'];
            $model->email = $_POST['email'];
            $model->address = $_POST['address'];
            $model->designer = $_POST['designer'];
            if ($model->save()){
                return $this->success(0);
            }else{
                $error = array_values($model->getErrors());
                return $this->error(400, $error[0][0]);
            }
        }
    }

    /***
     * 用户列表
     */
    public function actionList()
    {

        $limit = $_GET['limit'];
        $page = $_GET['page'];
        $offset = ($page - 1) * $limit;

        $user = Admin::find()->where(['role'=>'buyer'])->asArray()->limit($limit)->offset($offset)->all();

        $data['code'] = 0;
        $data['count'] = Admin::find()->where(['role'=>'buyer'])->asArray()->count();

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

}