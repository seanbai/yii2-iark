<?php

namespace backend\controllers;

class ManufactuerCompletedController extends Controller
{

    public function actionIndex()
    {
        $data = [];
        return $this->render('index',$data);
    }


}