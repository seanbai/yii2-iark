<?php

namespace backend\controllers;

class HomeController extends Controller
{

    public function actionIndex()
    {
        $data = [];
        return $this->render('index',$data);
    }


}