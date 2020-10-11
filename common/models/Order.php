<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $last_time
 * @property integer $last_ip
 * @property string $password write-only password
 */
class Order extends ActiveRecord
{
    /** 推送状态 */
    const STATUS_NEW = 1;               //新建
    const STATUS_WAITING_PUSH = 2;      //等待同步
    const STATUS_PUSHING = 3;           //同步中
    const STATUS_PUSH_FAILED = 4;       //同步失败
    const STATUS_PUSH_SUCCESS = 5;      //产品上传成功

}
