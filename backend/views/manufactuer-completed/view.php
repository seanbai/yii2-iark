<?php
// 定义标题和面包屑信息
$this->title = 'My Order';
?>
<?=\backend\widgets\MeTable::widget()?>
<?php $this->beginBlock('javascript') ?>

<style>
    #main-container {
        display: none;
    }
    input[readonly] {
        background: #ffffff!important
    }
</style>

<div class="layui-fluid" >
    <div class="layui-row layui-col-space5">
        <div class="layui-col-md12">
            <form class="layui-form layui-form-pane" method="post">
                <input type="hidden" name="id" value="<?php echo $id?>">
                <!-- 报价 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">Price Quote</label>
                    <div class="layui-input-block">
                        <input type="text" name="price" lay-verify="title" autocomplete="off" placeholder="Input price quote..." class="layui-input">
                    </div>
                </div>
                <!-- 定金 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">Deposit</label>
                    <div class="layui-input-block">
                        <input type="text" name="deposit" lay-verify="title" autocomplete="off" placeholder="10,000" class="layui-input">
                    </div>
                </div>
                <!-- 定金确认 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">Prepayment</label>
                    <div class="layui-input-block">
                        <select name="prepayment"  disabled="disabled">
                            <option value="" selected="">Confirm Prepayment ?</option>
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                </div>
                <!-- 开启生产状态 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">In Production</label>
                    <div class="layui-input-block">
                        <select name="production"  disabled="disabled">
                            <option value="" selected="">In Production ?</option>
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                </div>
                <!-- 尾款申请 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">Balance</label>
                    <div class="layui-input-block">
                        <select name="balance" disabled="disabled">
                            <option value="" selected="">Application for balance payment ?</option>
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                </div>
                <!-- 允许发货 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">Delivery</label>
                    <div class="layui-input-block">
                        <select name="delivery" disabled="disabled">
                            <option value="" selected="">Permitted delivery ?</option>
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                </div>
                <!-- 订单完成 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">Completed</label>
                    <div class="layui-input-block">
                        <select name="completed" disabled="disabled">
                            <option value="" selected="">Order completed ?</option>
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
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
    }).use(['index', 'myorder']);
</script>

<?php $this->endBlock(); ?>