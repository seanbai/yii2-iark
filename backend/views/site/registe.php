<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = Yii::t('app', 'Login');
$this->params['breadcrumbs'][] = $this->title;
?>


<?php $form = ActiveForm::begin(); ?>
<div class="layui-form">
    <div class="layui-form-item" wid100>
        <div class="layui-input-block">
            <?= $form->field($model, 'username')->textInput(['placeholder' => $model->getAttributeLabel('username'),'class'=> 'layui-input'])->label(false) ?>
        </div>
    </div>
    <div class="layui-form-item" wid100>
        <div class="layui-input-block">
            <?= $form->field($model, 'password')->textInput(['placeholder' => $model->getAttributeLabel('password'),'class'=> 'layui-input'])->label(false) ?>
        </div>
    </div>
    <div class="layui-form-item" wid100>
        <div class="layui-input-block">
            <?= Html::submitButton(' Log In', ['class' => 'layui-btn layui-btn-fluid layui-btn-lg']) ?>
        </div>
    </div>
    <div class="layui-form-item" wid100>
        <div class="layui-input-block">
<!--            注册-->
        </div>
    </div>
</div>


<?php ActiveForm::end(); ?>
