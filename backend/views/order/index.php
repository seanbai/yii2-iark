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
    </style>


<div class="layui-fluid">
    <div class="layui-row layui-col-space5">
        <div class="layui-col-sm6">
            <div class="layui-card">
                <div class="layui-card-header">
                    Workflow Pending
                </div>
                <div class="layui-card-body">
                    <!-- table black -->
                    <table id="workflow" lay-filter="flow"></table>
                    <!-- tool bar -->
                    <script type="text/html" id="toolbar">
                        <div class="layui-btn-container">
                            <button class="layui-btn layui-btn-sm" lay-event="status">Change Status</button>
                            <button class="layui-btn layui-btn-sm" lay-event="ignore">Ignore the Order</button>
                        </div>
                    </script>
                </div>
            </div>
        </div>

        <!-- -->
        <div class="layui-col-sm6">
            <div class="layui-card">
                <div class="layui-card-header">
                    Order Details - #GZL2018101800004
                </div>
                <div class="layui-card-body">
                    <!-- table black -->
                    <table id="products" lay-filter="products"></table>
                </div>
            </div>
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