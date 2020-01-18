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

    <div class="layui-fluid">
        <div class="layui-row layui-col-space5">
            <div class="layui-col-md12">
                <form class="layui-form layui-form-pane" id="updateFrom" action="" method="post">
                    <!-- 平台方报价 -->
                    <input hidden="hidden" name="id" value="<?php echo $id ?>">
                    <input hidden="hidden" name="status" value="<?php echo $status ?>">
                    <div class="layui-form-item">
                        <label class="layui-form-label">报价</label>
                        <div class="layui-input-block">
                                <select id="quote" name="quote" <?php if ($status != 5) echo 'disabled' ?>>
                                <option value="0">报价待确定</option>
                                <option value="1">确定报价</option>
                                <option value="2">拒绝报价</option>
                            </select>
                        </div>
                    </div>
                    <!-- 定金支付 -->
                    <div class="layui-form-item">
                        <label class="layui-form-label">定金</label>
                        <div class="layui-input-block">
                            <select id="deposit" name="deposit" <?php if ($status != 6) echo 'disabled' ?>>
                                <option value="0">定金待支付</option>
                                <option value="1">定金已支付</option>
                            </select>
                        </div>
                    </div>
                    <!-- 尾款申请 -->
                    <div class="layui-form-item">
                        <label class="layui-form-label">尾款</label>
                        <div class="layui-input-block">
                            <select id="balance" name="balance" <?php if ($status != 5) echo 'disabled' ?>>
                                <option value="0">尾款待支付</option>
                                <option value="1">尾款已支付</option>
                            </select>
                        </div>
                    </div>
                    <!-- 尾款确认 -->
                    <div class="layui-form-item">
                        <label class="layui-form-label">税金&运费</label>
                        <div class="layui-input-block">
                            <select id="taxes" name="taxes" <?php if ($status != 5) echo 'disabled' ?>>
                                <option value="0">税金待支付</option>
                                <option value="1">税金已支付</option>
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
    }).use(['index', 'myorder']);
</script>

<?php $this->endBlock(); ?>