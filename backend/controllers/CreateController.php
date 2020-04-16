<?php

namespace backend\controllers;

use backend\models\AdminLog;
use backend\models\Order;
use backend\models\Product;
use backend\models\System;
use backend\modules\api\messages\ApiMsg;
use common\helpers\Curl;
use common\helpers\Dir;
use common\helpers\Helper;
use common\helpers\Image;
use common\models\Log;
use console\models\ProductPicture;
use yii\db\Exception;
use common\models\Create;
use yii\web\Response;

/**
 * Class SystemController 系统配置 执行操作控制器
 * @package backend\controllers
 */
class CreateController extends Controller
{

    private $count = 12;


    private $_type = ['zip','png'];

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

    /**
     * 删除
     *
     *  auth rule: create/delete
     *
     * @return mixed|string
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete()
    {
        // 接收参数判断
        $data = \Yii::$app->request->post();
        $model = Product::findOne($data['id']);
        if (!$model) {
            return $this->returnJson();
        }

        // 删除数据成功
        if ($model->delete()) {
            @unlink(\Yii::$app->basePath . '/web/'. $model->image);
            AdminLog::create(AdminLog::TYPE_DELETE, $data, $this->pk . '=' . $data[$this->pk]);
            return $this->success($model);
        } else {
            return $this->error(1004, Helper::arrayToString($model->getErrors()));
        }
    }


    /**
     * 订单产品
     * auth rule: create/items
     * @return mixed|string
     */
    public function actionItems()
    {
        $items = Product::find()
        ->where(['user_id' =>  \Yii::$app->user->id])
        ->asArray()
        ->all();
        foreach ($items as $key => &$item){
            $item['pid'] = $item['id'];
            $item['id'] = $key + 1;
        }
        $data = [
            'code' => 0,
            'msg' => '',
            'count' => count($items),
            'data' => $items
        ];
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return$data;
    }

    /**
     * 添加产品
     *  auth rule: create/product
     * @noinspection DuplicatedCode
     */
    public function actionProduct()
    {
        if(strtolower(\Yii::$app->request->method ) != 'post'){
            return $this->error(1001,"请求资源错误，请稍后再试！");
        }
        $data = \Yii::$app->request->post();
        if(!$data['brand'] || !$data['model'] || !$data['size'] || !$data['qty']){
            return $this->error(1001,"请检查您的字段是否填写正确！");
        }
        unset($data['_csrf']);
        $product = new Product();
        $data['create_time'] = date('Y-m-d H:i:s');
        $data['user_id'] = \Yii::$app->user->id;
        if (!$product->load($data, '')) {
            return $this->error(1001);
        }
        // 判断修改返回数据
        if ($product->save()) {
            $this->handleJson($product);
            $pk = $this->pk;
            AdminLog::create(AdminLog::TYPE_CREATE, $data, $this->pk . '=' . $product->$pk);
            return $this->returnJson([
                'errCode' => 0,
                'errMsg' => '',
                'data' => []
            ]);
        } else {
            return $this->error(1001, Helper::arrayToString($product->getErrors()));
        }
    }

    /**
     * 创建订单
     * @return mixed|string
     * @throws Exception
     */
    public function actionFrom()
    {
        $postData = \Yii::$app->request->post();
        $orderItems  = Product::find()
            ->where(['user_id' =>  \Yii::$app->user->id])
            ->asArray()
            ->all();
        if(empty($orderItems)){
            return $this->error(300,"请先添加产品");
        }
        if(!$postData['delivery'] || !$postData['address'] || !$postData['contact']
         || !$postData['project']) {
            return $this->error(300,"订单信息不完整，请检查后再下单。");
        }
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            $number = $this->getRand(3).date("YmdHis",time());
            $order = [
                'order_number' => $number,
                'user' => \Yii::$app->user->id,
                'date' => $postData['delivery'],
                'package' => $postData['package'],
                'address' => $postData['address'],
                'name' => $postData['contact'],
                'project_name' => $postData['project'],
                'create_time'=> date("Y:m:d H:i:s",time()),
            ];
            \Yii::$app->db->createCommand()->insert(Order::tableName(),$order)->execute();
            $createId = \Yii::$app->db->getLastInsertID();
            $columns = ['order_id','order_number','brand','number','type','desc','size','material','files','create_time'];
            $purchaseItems = [];
            foreach ($orderItems as $_orderItem){
                $purchaseItems[] = [
                    'order_id' => $createId,
                    'order_number' => $number,
                    'brand'  =>  $_orderItem['brand'],
                    'number' =>  $_orderItem['qty'],
                    'type'   =>  $_orderItem['model'],
                    'desc'   =>  $_orderItem['desc'],
                    'size'   =>  $_orderItem['size'],
                    'material' => $_orderItem['material'],
                    'files'  =>  $_orderItem['image'],
                    'create_time'=> date("Y:m:d H:i:s",time()),
                ];
            }
            \Yii::$app->db->createCommand()->batchInsert(Order::tableItemName(), $columns,  $purchaseItems)->execute();

            $this->createStatusList($createId,$number);
            $transaction->commit();
        }catch (\Exception $exception){
            $transaction->rollBack();
            return $this->error(300,"系统异常,请稍后再试");
        }
        //下单完成删除临时产品表
        Product::deleteAll(['user_id' =>  \Yii::$app->user->id]);
        return $this->success([],"订单创建成功！");
    }

    public function actionFromBackUp(){

        //数据验证-电话-地址-付款方式
        $data = $_POST;
        $files = $_FILES;
        $check = $this->checkData($data,$files);
        $err = [];
        /** 返回成功数据 */
        if ($check == 0) return $this->error(201,"请检查您的字段是否填写正确");
        $upload = $this->files($files['files']);
        if ($upload == 0) return $this->error(301,"系统异常,请稍后再试");

        $data['files'] = $upload;

        try{
            $number = $this->getRand(3).date("YmdHis",time());
            $order = [
                'order_number' => $number,
                'user' =>  $data['userId'],
                'date' => $data['date'],
                'order_status' => 1,
                'name' =>  $data['name'],
                'phone' =>  $data['phone'],
                'address' => $data['address'],
                'currency' => $data['currency'],
                'create_time'=> date("Y:m:d H:i:s",time()),
            ];
            \Yii::$app->db->createCommand()->insert(Order::tableName(),$order)->execute();
            $createId = \Yii::$app->db->getLastInsertID();

            $orderItem = [];
            $columns = ['order_id','order_number','brand','number','type','desc','files','create_time'];
            foreach ($data['brand'] as $key=>$value){
                if (empty($value)) continue;
                $orderItem[] = [
                    'order_id' => $createId,
                    'order_number' => $number,
                    'brand' =>  $data['brand'][$key],
                    'number' => $data['number'][$key],
                    'type' =>  $data['type'][$key],
                    'desc' =>  $data['desc'][$key],
                    'files' => $data['files'][$key],
                    'create_time'=> date("Y:m:d H:i:s",time()),
                ];
            }
            \Yii::$app->db->createCommand()->batchInsert(Order::tableItemName(), $columns, $orderItem)->execute();

            $this->createStatusList($createId,$number);

            return 1;
        }catch (Exception $exception){
            Order::deleteAll(['order_id'=>$createId]);
            return $this->error(300,"系统异常,请稍后再试");
        }
    }

    public function checkData($data,$files){
        if ( empty($data['phone']) || empty($data['address']) || empty($data['currency'])) {
            return 0;
        }
        if (empty($data['brand'][0]) || empty($data['type'][0]) || empty($data['number'][0])){
            return 0;
        }
        if (empty($files['files']['name'][0])){
            return 0;
        }
        return 1;
    }

    public function getRand($length = 3,$mess=''){
        // 密码字符集，可任意添加你需要的字符
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $str = $mess."";
        for ( $i = 0; $i < $length; $i++ )
        {
            $str .= $chars[ mt_rand(0, strlen($chars) - 1) ];
        }
        return $str ;
    }

    public function files($files){
        $img_root = \Yii::getAlias('@backend/web');
        $path = (new Dir($img_root))->getPath(\Yii::$app->params['product_download_dir']);
        $type = $request = [];
        foreach ($files['name'] as $key=>$value){
            if (!empty($value)){
                $ext = explode(".", $value);
                $ext = $ext[count($ext) - 1];
                if (!in_array($ext,$this->_type)){
                    return Create::NOT_UNION_TYPE;
                }
                $type[] = $ext;
            }
        }
        foreach ($files['tmp_name'] as $key=>$value){
            if (!empty($value)){
                do{
                    $new_name = $this->getRand(12,"Aa_").uniqid().'.'.$type[$key];
                }while (file_exists("../" . $img_root.'/'.$path));//检查图片是否存在文件夹，存在返回ture,否则false

                if (move_uploaded_file($value, $img_root.$path.'/'.$new_name)){
                    $request[] = $path.'/'.$new_name;;
                }else{
                    return 0;
                }
            }
        }
        return $request;
    }


    public function get_file_name($len)//获取一串随机数字，用于做上传到数据库中文件的名字
    {
        $new_file_name = 'A_';
        $chars = "1234567890qwertyuiopasdfghjklzxcvbnm";//随机生成图片名
        for ($i = 0; $i < $len; $i++) {
            $new_file_name .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $new_file_name;
    }



    public function createStatusList($id,$number)
    {
        $status = $this->count;
        $columns = ['order_id','number','type','status','create_time'];
        $item = [];
        for ($i=1;$i<=$status;$i++)
        {
            $item[] = [
                'order_id' => $id,
                'number' => $number,
                'type' =>  $i,
                'status' => 0,      //待处理
                'create_time'=> date("Y:m:d H:i:s",time()),
            ];
        }
        \Yii::$app->db->createCommand()->batchInsert('order_status', $columns, $item)->execute();
    }

}
