
<script src="/public/admin/lib/echarts.min.js"></script>

<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <!--  -->
        <div class="layui-col-md5">
            <div class="layui-row layui-col-space15">
                <div class="layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-header">
                            Anouncements
                        </div>
                        <div class="layui-card-body">
                            <h3>Incredibly Positive Reviews</h3>
                            <time>25/07/2019</time>
                            <p>To start a blog, think of a topic about and first brainstorm party is ways to write details</p>
                        </div>
                    </div>
                </div>
                <div class="layui-col-md6">
                    <div class="layui-card">
                        <div class="layui-card-header">
                            Order
                        </div>
                        <div class="layui-card-body">
                            <h3>37</h3>
                            <p>系统内采购单数量统计</p>
                        </div>
                    </div>
                </div>
                <!-- order number -->
                <div class="layui-col-md6">
                    <div class="layui-card">
                        <div class="layui-card-header">
                            Price
                        </div>
                        <div class="layui-card-body">
                            <h3>¥790,800</h3>
                            <p>历史采购金额统计</p>
                        </div>
                    </div>
                </div>
                <!--  pending order -->
                <div class="layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-header">Pending Order</div>
                        <div class="layui-card-body">
                            <table class="pending-order" id="pending-order"></table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!--  -->
        <div class="layui-col-md7">
            <div class="layui-card">
                <div class="layui-card-header">
                    PROJECTIONS VS ACTUALS
                </div>
                <div class="layui-card-body" style="height:400px">
                    <div id="main" style="height:400px"></div>
                </div>
            </div>

            <div class="layui-card">
                <div class="layui-card-header">Completed Order</div>
                <div class="layui-card-body">
                    <table class="completed-list" id="completed"></table>
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
    }).use(['index', 'console']);
</script>