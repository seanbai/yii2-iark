<?php

namespace backend\controllers;

class PurchaserCompletedController extends Controller
{

    public function actionIndex()
    {
        $data = [];
        return $this->render('index',$data);
    }


}