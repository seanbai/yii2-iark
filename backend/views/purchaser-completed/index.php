


<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">已完成订单</div>
                <div class="layui-card-body">
                    <table id="completed" lay-filter="completed"></table>
                    <!-- tool bar -->
                    <script type="text/html" id="completedBar">
                        <div class="layui-btn-container">
                            <button class="layui-btn layui-btn-sm" lay-event="details">查看产品清单</button>
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
    }).use(['index', 'completed']);
</script>
