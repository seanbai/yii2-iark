<?php

namespace backend\controllers;

class PurchaserUserController extends Controller
{

    public function actionIndex()
    {
        $data = [];
        return $this->render('index',$data);
    }

    public function actionCreate()
    {
        $data = [];
        return $this->render('create',$data);
    }

}