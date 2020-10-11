<?php


namespace backend\models;


use yii\db\ActiveRecord;


/**
 * @property string  $comment
 * @property string  $created_at
 * @property string  $status
 * @property int     $supplier_order_id
 * @package backend\models
 */
class OrderComment extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'supplier_order_comment';
    }
}