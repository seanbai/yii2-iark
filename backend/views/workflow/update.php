

<style>
    body{
        background-color: #FFFFFF;
    }
</style>

<div class="layui-fluid">
    <div class="layui-row layui-col-space5">
        <div class="layui-col-md12">
            <form class="layui-form layui-form-pane" method="post">
                <input type="hidden" name="id" value="<?php echo $id ?>">
                <input type="hidden" name="status" value="<?php echo $status ?>">
                <!-- 报价 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">是否需要报价</label>
                    <div class="layui-input-block">
                        <select name="bj" id="bj" lay-filter="bj" <?php if ($status != 1) echo 'disabled' ?> >
                            <option value="1">是</option>
                            <option value="2">否</option>
                        </select>
                    </div>
                </div>
                <!-- 供货商报价 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">供货商报价</label>
                    <div class="layui-input-block">
                        <input type="text" name="gPrice" id="gPrice" lay-verify="title" autocomplete="off" class="layui-input layui-disabled" disabled >
                    </div>
                </div>
                <!-- 平台方报价 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">平台方报价</label>
                    <div class="layui-input-block">
                        <input type="text" name="pPrice" id="pPrice" lay-verify="title" autocomplete="off" class="layui-input" <?php if ($status != 4 && $status != 3) echo 'disabled' ?>>
                    </div>
                </div>
                <!-- 定金确认 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">定金确认</label>
                    <div class="layui-input-block">
                        <select name="deposit" <?php if ($status != 6) echo 'disabled' ?>>
                            <option value="0">定金已到账</option>
                            <option value="1" >未到账</option>
                        </select>
                    </div>
                </div>
                <!-- 尾款申请 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">尾款待收</label>
                    <div class="layui-input-block">
                        <select name="balance" <?php if ($status != 8) echo 'disabled' ?>>
                            <option value="1">Yes</option>
                            <option value="2" >No</option>
                        </select>
                    </div>
                </div>
                <!-- 尾款确认 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">尾款确认</label>
                    <div class="layui-input-block">
                        <select name="balanceEnd" <?php if ($status != 10) echo 'disabled' ?>>
                            <option value="0">尾款已到账</option>
                            <option value="1" >未到账</option>
                        </select>
                    </div>
                </div>
                <!-- 平台方报价 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">报关单号</label>
                    <div class="layui-input-block">
                        <input type="text" name="shut" id="shut" lay-verify="title" autocomplete="off" class="layui-input" <?php if ($status != 12) echo 'disabled' ?>>
                    </div>
                </div>
                <!-- 允许状态 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">物流单号</label>
                    <div class="layui-input-block">
                        <input type="text" name="logistics" id="logistics" lay-verify="title" autocomplete="off" class="layui-input" <?php if ($status != 7) echo 'disabled' ?>>
                    </div>
                </div>
                <!-- 订单完成 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">订单完成</label>
                    <div class="layui-input-block">
                        <select name="orderEnd"  <?php if ($status != 8) echo 'disabled' ?>>
                            <option value="" selected="">订单已完成 ?</option>
                            <option value="0">是</option>
                            <option value="1">否</option>
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
    }).use(['index', 'workflow']);
</script>
