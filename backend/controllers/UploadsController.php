<?php

namespace backend\controllers;


use common\helpers\Dir;
use common\models\Create;

/**
 * Class UploadsController 上传文件 执行操作控制器
 * @package backend\controllers
 */
class UploadsController extends Controller
{
    /**
     * @var string 定义使用的model
     */
    public $modelClass = 'common\models\Uploads';

    private $_type = ['jpeg','png','jpg','gif'];
    protected $_type2 = ['zip','rar','7z'];
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
    public function actionUpload()
    {
        $files  = $_FILES;
        $upload = $this->files($files['file']);
        if ($upload) {
            return json_encode(['code'=>200,'data'=>$upload]);
        } else {
            return json_encode(['code'=>400,'data'=>$upload]);
        }

    }
    public function actionUploads()
    {
        $files  = $_FILES;
        $upload = $this->files2($files['file']);
        if ($upload) {
            return json_encode(['code'=>200,'data'=>$upload]);
        } else {
            return json_encode(['code'=>400,'data'=>$upload]);
        }

    }
    public function files2($files){
        $img_root = \Yii::getAlias('@backend/web');
        $path = (new Dir($img_root))->getPath(\Yii::$app->params['product_download_dir']);
        $request = [];
        $ext = explode(".", $files['name']);
        $ext = $ext[count($ext) - 1];
        if (!in_array($ext,$this->_type2)){
            return '';
        }
        $type = $ext;
        $new_name = $this->getRand(6,"File_").uniqid().'.'.$type;
        $up_dir = $img_root.$path;
        if (!is_dir($up_dir)) {
            mkdir($up_dir, 0777, true);
        }
        if (move_uploaded_file($files['tmp_name'], $img_root.$path.'/'.$new_name)) {
            return $path.'/'.$new_name;
//            return $new_name;
        }
        return '';
    }
    public function files($files){
        $img_root = \Yii::getAlias('@backend/web');
        $path = (new Dir($img_root))->getPath(\Yii::$app->params['product_download_dir']);
        $request = [];
        $ext = explode(".", $files['name']);
        $ext = $ext[count($ext) - 1];
        if (!in_array($ext,$this->_type)){
            return '';
        }
        $type = $ext;
        $new_name = $this->getRand(8,"Az_").uniqid().'.'.$type;
        $up_dir = $img_root.$path;
        if (!is_dir($up_dir)) {
            mkdir($up_dir, 0777, true);
        }
        if (move_uploaded_file($files['tmp_name'], $img_root.$path.'/'.$new_name)) {
            return $path.'/'.$new_name;
        }
        return '';
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
}
