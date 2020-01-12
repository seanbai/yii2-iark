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
<div class="layui-form" id="login">
    <div class="layui-form-item" wid100>
        <div class="layui-input-block">
            <?= $form->field($model, 'username')->textInput(['placeholder' => $model->getAttributeLabel('username'),'class'=> 'layui-input'])->label(false) ?>
        </div>
    </div>
    <div class="layui-form-item" wid100>
        <div class="layui-input-block">
            <?= $form->field($model, 'password')->passwordInput(['placeholder' => $model->getAttributeLabel('password'),'class'=> 'layui-input'])->label(false) ?>
        </div>
    </div>
    <div class="layui-form-item" wid100>
        <div class="layui-input-block">
            <?= Html::submitButton(' Log In', ['class' => 'layui-btn layui-btn-fluid layui-btn-lg']) ?>
        </div>
    </div>
<!--    <div class="layui-form-item" wid100>-->
<!--        <div class="layui-input-block">-->
<!--            <span id="zhuce" style="float: right;">注册</span>-->
<!--        </div>-->
<!--    </div>-->
</div>




<script type="text/javascript">

    $(function(){
        $(document).on('click', '#zhuce', function () {
            $('#reg').attr('style','display:block');
            $('#login').attr('style','display:none');
        })

    });
</script>



<?php ActiveForm::end(); ?>
