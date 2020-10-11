

<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">Completed Order</div>
                <div class="layui-card-body">
                    <table id="completed" lay-filter="completed"></table>
                    <!-- tool bar -->
                    <script type="text/html" id="completedBar">
                        <div class="layui-btn-container">
                            <button class="layui-btn layui-btn-sm" lay-event="details">Products List</button>
                        </div>
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 产品清单 -->
<div style="display:none" id="showItems" class="layui-fluid">
    <div class="layui-row layui-col-space10">
        <div class="layui-col-md12">
            <table class="items" id="items" lay-filter="items"></table>
        </div>
        <!-- 顶部工具栏 -->
        <script type="text/html" id="showItemsBar">
    </script>
    </div>
</div>

<script src="/public/admin/ui/layui.js"></script>
<script>
    layui.config({
        base: '/public/admin/' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['index', 'completed2']);
</script>