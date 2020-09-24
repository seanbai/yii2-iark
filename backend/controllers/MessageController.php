<?php
namespace backend\controllers;

use backend\models\Message;

class MessageController extends Controller
{

    public function actionIndex()
    {
        print_r(123);
        die;
    }

    /**
     * 保存留言信息
     */
    public function actionSave()
    {
        $orderId = $_GET['orderId'];
        $type = $_GET['type'];
        $text = $_GET['content'];

        if (empty($orderId) || empty($type) || empty($text)) return $this->error(500,"保存留言信息失败");

        $model = Message::findOne(['order_id' => $orderId, 'type' => $type]);
        if (empty($model)) $model = new Message();
        $model->order_id = $orderId;
        $model->type = $type;
        $model->text = (string)$text;
        $model->created_at = (string)time();
        $model->updated_at = (string)time();
        if (!$model->save()) return $this->error(500, $model->errors);

        return $this->success();
    }



}