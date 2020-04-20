<?php


namespace backend\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "system".
 *
 * @property int $id
 * @property string $brand
 * @property int $qty
 * @property string $model
 * @property string $desc
 * @property string $image
 * @property string $size
 * @property string $package
 * @property string $att
 * @property string $supplier_name
 * @property string $material
 * @property int $user_id
 * @property string $create_time
 */
class Product extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['qty', 'user_id'], 'integer'],
            [['brand','supplier_name','model', 'size','qty'], 'required'],
            [['att', 'image'], 'safe'],
            [['brand','model','desc','size', 'att', 'image', 'package','material','create_time'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'brand' => '名称',
            'supplier_name' => '供应商',
            'att' => '附件',
            'qty'  => '数量',
            'model' => '型号',
            'desc' => '备注',
            'size' => '尺寸',
            'image' => '图片',
            'package' => '包装',
            'material' => '材质',
            'create_time' => '创建时间',
        ];
    }

}