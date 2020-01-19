<?php
// 定义标题和面包屑信息
$this->title = 'My Order';
?>
<?= \backend\widgets\MeTable::widget() ?>
<?php $this->beginBlock('javascript') ?>

<style>
    #main-container {
        display: none;
    }

    input[readonly] {
        background: #ffffff !important
    }
</style>

<div class="layui-fluid " id="order-status">
    <div class="layui-row layui-col-space5">
        <div class="layui-col-md12">
            <form class="layui-form layui-form-pane" action="" method="post">

                <input hidden="hidden" name="id" value="<?php echo $id ?>">
                <input hidden="hidden" name="status" value="<?php echo $model['order_status']?>">

                <!-- 定金确认 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">Prepayment</label>
                    <div class="layui-input-block">
                        <select name="prepayment" <?php if ($model['order_status'] != 10) echo 'disabled' ?>>
                            <option value="0">Confirm Prepayment ?</option>
                            <option value="1">Yes</option>
                            <option value="2">No</option>
                        </select>
                    </div>
                </div>
                <!-- 开启生产状态 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">In Production</label>
                    <div class="layui-input-block">
                        <select name="in_production"  <?php if ($model['order_status'] != 11) echo 'disabled' ?>>
                            <option value="0">In Production ?</option>
                            <option value="1">Yes</option>
                            <option value="2">No</option>
                        </select>
                    </div>
                </div>
                <!-- 尾款申请 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">Balance Application</label>
                    <div class="layui-input-block">
                        <select name="balance" <?php if ($model['order_status'] != 12) echo 'disabled' ?>>
                            <option value="0">Application for balance payment ?</option>
                            <option value="1">Yes</option>
                            <option value="2">No</option>
                        </select>
                    </div>
                </div>
                <!-- 尾款确认 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">Final Payment</label>
                    <div class="layui-input-block">
                        <select name="final" <?php if ($model['order_status'] != 15) echo 'disabled' ?>>
                            <option value="0">Confirm receipt of balance ?</option>
                            <option value="1">Yes</option>
                            <option value="2">No</option>
                        </select>
                    </div>
                </div>
                <!-- 提货 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">Pick Up</label>
                    <div class="layui-input-block">
                        <select name="pick"<?php if ($model['order_status'] != 14) echo 'disabled' ?>>
                            <option value="0">Is it possible to pick up ?</option>
                            <option value="1">Yes</option>
                            <option value="2">No</option>
                        </select>
                    </div>
                </div>
                <!-- 提交表单 -->
                <div class="layui-form-item">
                    <button class="layui-btn" lay-submit="" lay-filter="update">保存信息</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="/public/admin/ui/layui.js"></script>
<script>
    layui.config({
        base: '/public/admin/' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['index', 'order-manufacturer']);
</script>

<?php $this->endBlock(); ?>