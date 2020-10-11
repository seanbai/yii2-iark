<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\helpers\Json;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "system".
 *
 * @property int $id
 * @property string $title
 * @property string $domain
 * @property string $domain_title
 * @property string $update_size
 * @property string $rate
 */
class System extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'system';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'update_size', 'rate'], 'string', 'max' => 45],
            [['domain'], 'string', 'max' => 100],
            [['domain_title'], 'string', 'max' => 40],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'domain' => 'Domain',
            'domain_title' => 'Domain Title',
            'update_size' => 'Update Size',
            'rate' => 'Rate',
        ];
    }


    /**
     * @param $data
     */
    public function from($data)
    {

    }
}
