
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">新订单</div>
                <div class="layui-card-body">
                    <table id="neworder" lay-filter="neworder"></table>
                    <!-- tool bar -->
                    <script type="text/html" id="newOrderBar">
                        <div class="layui-btn-container">
                            <button class="layui-btn layui-btn-sm " lay-event="details">查看产品清单</button>
                            <button class="layui-btn layui-btn-sm " lay-event="ownerInfo">采购商信息</button>
                            <button class="layui-btn layui-btn-sm " lay-event="confirm">确认下单</button>
                            <button class="layui-btn layui-btn-sm " lay-event="cancel">取消采购</button>
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
    }).use(['index', 'neworder']);
</script>
</body>
</html>

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
