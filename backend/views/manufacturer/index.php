

<div class="layui-fluid">
    <div class="layui-row layui-col-space5">
        <div class="layui-col-sm6">
            <div class="layui-card">
                <div class="layui-card-header">
                    Pending Order
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
                    Order Details - #
                </div>
                <div class="layui-card-body">
                    <!-- table black -->
                    <table id="products" lay-filter="products"></table>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="layui-fluid hide" id="order-status">
    <div class="layui-row layui-col-space5">
        <div class="layui-col-md12">
            <form class="layui-form layui-form-pane" action="" method="post">
                <!-- 报价 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">Price Quote</label>
                    <div class="layui-input-block">
                        <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="Input price quote..." class="layui-input">
                    </div>
                </div>
                <!-- 定金 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">Deposit</label>
                    <div class="layui-input-block">
                        <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="50,000" class="layui-input" disabled="disabled">
                    </div>
                </div>
                <!-- 定金确认 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">Prepayment</label>
                    <div class="layui-input-block">
                        <select name="interest">
                            <option value="" selected="">Confirm Prepayment ?</option>
                            <option value="0">Yes</option>
                            <option value="1" >No</option>
                        </select>
                    </div>
                </div>
                <!-- 开启生产状态 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">In Production</label>
                    <div class="layui-input-block">
                        <select name="interest">
                            <option value="" selected="">In Production ?</option>
                            <option value="0">Yes</option>
                            <option value="1" >No</option>
                        </select>
                    </div>
                </div>
                <!-- 尾款申请 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">Balance</label>
                    <div class="layui-input-block">
                        <select name="interest" disabled="disabled">
                            <option value="" selected="">Application for balance payment ?</option>
                            <option value="0">Yes</option>
                            <option value="1" >No</option>
                        </select>
                    </div>
                </div>
                <!-- 允许发货 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">Delivery</label>
                    <div class="layui-input-block">
                        <select name="interest" disabled="disabled">
                            <option value="" selected="">Permitted delivery ?</option>
                            <option value="0">Yes</option>
                            <option value="1" >No</option>
                        </select>
                    </div>
                </div>
                <!-- 订单完成 -->
                <div class="layui-form-item">
                    <label class="layui-form-label">Completed</label>
                    <div class="layui-input-block">
                        <select name="interest" disabled="disabled">
                            <option value="" selected="">Order completed ?</option>
                            <option value="0">Yes</option>
                            <option value="1" >No</option>
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
