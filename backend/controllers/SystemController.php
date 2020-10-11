<?php

namespace backend\controllers;

use backend\models\System;
use yii\db\Exception;

/**
 * Class SystemController 系统配置 执行操作控制器
 * @package backend\controllers
 */
class SystemController extends Controller
{
    /**
     * @var string 定义使用的model
     */
    public $modelClass = 'backend\models\System';
     
    /**
     * 查询处理
     * @param  array $params
     * @return array 返回数组
     */
    public function where($params)
    {
        return [
            
        ];
    }

    public function actionIndex()
    {
        $data = System::find()->where(['id'=>1])->asArray()->one();

        return $this->render('index', $data);
    }

    public function actionFrom(){
        $data['title'] = \Yii::$app->request->get('title');      //系统名称
        $data['domain'] = \Yii::$app->request->get('domain');    //域名
        $data['header'] = \Yii::$app->request->get('header');    //浏览器名称
        $data['size'] = \Yii::$app->request->get('size');       //上传附件大小
        $data['rate'] = \Yii::$app->request->get('rate');       //汇率

        try{
            //保存数据
            $model = System::findOne(['id'=>1]);
            $model->title = $data['title'];
            $model->domain = $data['domain'];
            $model->domain_title = $data['header'];
            $model->update_size = $data['size'];
            $model->rate = $data['rate'];
            $model->save();

            return $this->success($data);

        }catch (Exception $exception){

        }


    }
}
