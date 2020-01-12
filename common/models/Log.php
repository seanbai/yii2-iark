<?php

namespace common\models;

use Yii;
use yii\log\Logger;

/**
 * This is the model class for table "log".
 *
 * @property string $id
 * @property string $ip 请求IP
 * @property int $level 状态 1跟踪 2通知 3警告 4异常
 * @property string $description 描述
 * @property int $user_id 用户ID
 * @property string $user 请求用户
 * @property string $request_path 实际请求方法
 * @property string $request_url 请求路由
 * @property int $data_id 数据位置
 * @property string $request 请求参数
 * @property string $response 响应参数
 * @property string $created_at 创建时间
 */
class Log extends \yii\db\ActiveRecord
{
    /** 状态 */
    const TYPE_TRACE = 1;   //跟踪
    const TYPE_NOTICE = 2;   //通知
    const TYPE_WARNING = 3;  //警告
    const TYPE_ERR = 4;       //异常

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['level', 'user_id', 'data_id','created_at'], 'integer'],
            [['request', 'response'], 'string'],
            [['ip', 'description'], 'string', 'max' => 255],
            [['user'], 'string', 'max' => 100],
            [['request_path', 'request_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ip' => '请求IP',
            'level' => '类型',
            'description' => '描述',
            'user_id' => '用户ID',
            'user' => '用户名称',
            'request_path' => '请求方法',
            'request_url' => '请求路径',
            'data_id' => '数据位置',
            'request' => '请求参数',
            'response' => '响应参数',
            'created_at' => '创建时间',
        ];
    }

    /**
     * 添加日志
     * @param $ip
     * @param $level
     * @param $description
     * @param $user
     * @param $request_path
     * @param $request_url
     * @param $data_id
     * @param string $request
     * @param string $response
     * @return bool
     */
    public static function add($ip, $level, $description, $user, $request_path, $request_url, $data_id, $request = '', $response = ''){
        if (YII_ENV == 'prod' || !YII_DEBUG){
            if ($level == static::TYPE_TRACE) return null;
        }

        $log = new self();
        $log->ip = $ip;
        $log->level = $level;
        $log->description = $description;
        $log->user_id = $user->getId();
        $log->user = $user->getUsername();
        $log->request_url = $request_url;
        $log->request_path = $request_path;
        $log->data_id = $data_id;
        $log->request = is_string($request) ? $request : json_encode($request);
        $log->response = is_string($response) ? $response : json_encode($response);
        $log->created_at = time();

        if (!$log->save()) {
            $message = [
                'attributes' => $log->getAttributes(),
                'errorMsg' => $log->getErrors(),
            ];

            \Yii::$app->getLog()->getLogger()->log($message,Logger::LEVEL_ERROR, \Yii::$app->id);
            return false;
        }

        return true;
    }

    public static function getType($instance = null){
        $rtn = [
            static::TYPE_TRACE => '跟踪',
            static::TYPE_NOTICE => '通知',
            static::TYPE_WARNING => '警告',
            static::TYPE_ERR => '异常',
        ];
        if ($instance !== null)
            $rtn = isset($rtn[$instance]) ? $rtn[$instance] : '';

        return $rtn;
    }

    public static function getTypeColor($instance = null){
        $rtn = [
            static::TYPE_TRACE => '',
            static::TYPE_NOTICE => 'label-success',
            static::TYPE_WARNING => 'label-warning',
            static::TYPE_ERR => 'label-danger',
        ];

        if ($instance !== null)
            $rtn = isset($rtn[$instance]) ? $rtn[$instance] : '';

        return $rtn;
    }

    public function beforeValidate()
    {
        $this->data_id = intval($this->data_id);
        return parent::beforeValidate();
    }
}
