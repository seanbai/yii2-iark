<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">
                    Purchaser Management
                </div>
                <div class="layui-card-body">
                    <!-- table black -->
                    <table id="manufacturer" lay-filter="manufacturer"></table>
                    <!-- tool bar -->
                    <script type="text/html" id="toolbar">
                        <div class="layui-btn-container">
                            <button class="layui-btn layui-btn-sm" lay-event="create">添加供货商账户</button>
                            <button class="layui-btn layui-btn-sm" lay-event="disabled">账户停用</button>
                        </div>
                    </script>
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
    }).use(['index', 'manufacturer']);
</script>