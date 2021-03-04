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

        if ((!isset($_GET['delivery']) && !isset($_GET['orderId'])) || !isset($_GET['type']) || !isset($_GET['content']))  return $this->error(500,"保存留言信息失败");

        $orderId = isset($_GET['orderId']) ? $_GET['orderId'] : 999999;
        $type = $_GET['type'];
        $text = $_GET['content'];
        $deliveryId = isset($_GET['delivery']) ? $_GET['delivery'] : 0;

        $model = Message::findOne(['order_id' => $orderId, 'type' => $type]);
        if (empty($model) || $orderId == 999999) $model = new Message();
        $model->order_id = $orderId;
        $model->delivery = $deliveryId;
        $model->type = $type;
        $model->text = (string)$text;
        $model->created_at = (string)time();
        $model->updated_at = (string)time();
        if (!$model->save()) return $this->error(500, $model->errors);

        return $this->success();
    }


    public function actionGetMessages()
    {
        $orderId = $_GET['order_id'];

        return '留言信息';
    }

}