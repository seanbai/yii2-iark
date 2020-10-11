<?php

namespace common\models;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property int $order_number
 * @property int $status
 * @property string $delivery_time
 * @property int $payment_method
 * @property int $order_user
 * @property string $create_time
 */
class Create extends \yii\db\ActiveRecord
{


    const NOT_UNION_TYPE = 4;


    public function checkData ($data,$files) {

    }

}
