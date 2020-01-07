<?php

namespace backend\controllers;

class HelpController extends Controller
{

    public function actionIndex()
    {
        $data = [];
        return $this->render('index',$data);
    }


}