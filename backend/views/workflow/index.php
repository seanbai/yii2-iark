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
            <div class="layui-col-sm7">
                <div class="layui-card" id="leftCard">
                    <div class="layui-card-header">
                        Workflow Pending
                    </div>
                    <div class="layui-card-body">
                        <!-- table black -->
                        <table id="workflow" lay-filter="workflow"></table>
                        <!-- tool bar -->
                        <script type="text/html" id="toolbar">
                            <div class="layui-btn-container">
                                <button class="layui-btn layui-btn-sm" lay-event="enabled">Enabled</button>
                                <button class="layui-btn layui-btn-sm" lay-event="disabled">Disabled</button>
                                <button class="layui-btn layui-btn-sm" lay-event="reset">Reset Password</button>
                            </div>
                        </script>
                    </div>
                </div>
            </div>

            <!-- -->
            <div class="layui-col-sm5">
                <div class="layui-card" id="rightCard">
                    <div class="layui-card-header">
                        Order Details - #GZL2018101800004
                    </div>
                    <div class="layui-card-body">
                        <div class="layui-tab layui-tab-brief">
                            <ul class="layui-tab-title">
                                <li class="layui-this">Overview</li>
                                <li>Product List</li>
                                <li>Timeline</li>
                            </ul>
                            <div class="layui-tab-content">
                                <!-- workflow table -->
                                <div class="layui-tab-item layui-show">
                                    <!-- workflow table -->
                                    <table class="layui-table">
                                        <tbody>
                                        <tr>
                                            <th colspan="12">工作流程单</th>
                                        </tr>
                                        <tr>
                                            <td colspan="12">关联订单：</td>
                                        </tr>
                                        <tr>
                                            <td colspan="6">下单时间：</td>
                                            <td colspan="6">是否报价：</td>
                                        </tr>
                                        <!-- 报价段 -->
                                        <tr>
                                            <th colspan="12">报价</th>
                                        </tr>
                                        <tr>
                                            <td colspan="12">报价时间：</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">报价金额：</td>
                                            <td colspan="4">币种：</td>
                                            <td colspan="4">汇率：</td>
                                        </tr>
                                        <tr>
                                            <td colspan="12">报价备注：</td>
                                        </tr>
                                        <tr>
                                            <th colspan="12">确认报价</th>
                                        </tr>
                                        <tr>
                                            <td colspan="4">确认时间：</td>
                                            <td colspan="8">备注：</td>
                                        </tr>
                                        <tr>
                                            <th colspan="12">确认采购并支付定金</th>
                                        </tr>
                                        <tr>
                                            <td colspan="4">支付时间：</td>
                                            <td colspan="4">支付金额：</td>
                                            <td colspan="4">支付币种：</td>
                                        </tr>
                                        <tr>
                                            <th colspan="12">收款方确认定金</th>
                                        </tr>
                                        <tr>
                                            <td colspan="4">确认时间：</td>
                                            <td colspan="8">联系人与电话：</td>
                                        </tr>
                                        <!--  -->
                                        <tr>
                                            <th colspan="12">制造商生产</th>
                                        </tr>
                                        <tr>
                                            <td colspan="4">预计生产完成时间：</td>
                                            <td colspan="8">备注：</td>
                                        </tr>
                                        <!--  -->
                                        <tr>
                                            <th colspan="12">支付尾款</th>
                                        </tr>
                                        <tr>
                                            <td colspan="4">支付时间：</td>
                                            <td colspan="4">金额：</td>
                                            <td colspan="4">币种：</td>
                                        </tr>
                                        <!--  -->
                                        <tr>
                                            <th colspan="12">收款方确认尾款</th>
                                        </tr>
                                        <tr>
                                            <td colspan="4">确认时间：</td>
                                            <td colspan="8">联系人与电话：</td>
                                        </tr>
                                        <!--  -->
                                        <tr>
                                            <th colspan="12">工厂提货</th>
                                        </tr>
                                        <tr>
                                            <td colspan="4">日期：</td>
                                            <td colspan="8">说明：</td>
                                        </tr>
                                        <!--  -->
                                        <tr>
                                            <th colspan="12">报关</th>
                                        </tr>
                                        <tr>
                                            <td colspan="4">日期：</td>
                                            <td colspan="8">说明：</td>
                                        </tr>
                                        <!--  -->
                                        <tr>
                                            <th colspan="12">支付税金</th>
                                        </tr>
                                        <tr>
                                            <td colspan="4">支付时间：</td>
                                            <td colspan="8">金额：</td>
                                        </tr>
                                        <!--  -->
                                        <tr>
                                            <th colspan="12">收款方确认（税金）</th>
                                        </tr>
                                        <tr>
                                            <td colspan="4">支付时间：</td>
                                            <td colspan="8">金额：</td>
                                        </tr>
                                        <!--  -->
                                        <tr>
                                            <th colspan="12">抵达入库</th>
                                        </tr>
                                        <tr>
                                            <td colspan="4">支付时间：</td>
                                            <td colspan="8">金额：</td>
                                        </tr>
                                        <!--  -->
                                        <tr>
                                            <th colspan="12">整单结算</th>
                                        </tr>
                                        <tr>
                                            <td colspan="4">结算时间：</td>
                                            <td colspan="8">总金额：</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- products -->
                                <div class="layui-tab-item">

                                </div>
                                <!-- timeline -->
                                <div class="layui-tab-item">
                                    <ul class="layui-timeline">
                                        <li class="layui-timeline-item">
                                            <i class="layui-icon layui-timeline-axis"></i>
                                            <div class="layui-timeline-content">
                                                <h4 class="layui-timeline-title">报价</h4>
                                                <p><time>25/07/2019</time> - Robert</p>
                                            </div>
                                        </li>

                                        <li class="layui-timeline-item">
                                            <i class="layui-icon layui-timeline-axis"></i>
                                            <div class="layui-timeline-content">
                                                <h4 class="layui-timeline-title">确认报价</h4>
                                                <p><time>25/07/2019</time> - Robert</p>
                                            </div>
                                        </li>

                                        <li class="layui-timeline-item">
                                            <i class="layui-icon layui-timeline-axis"></i>
                                            <div class="layui-timeline-content">
                                                <h4 class="layui-timeline-title">同意报价并支付定金</h4>
                                                <p><time>25/07/2019</time> - Robert</p>
                                            </div>
                                        </li>

                                        <li class="layui-timeline-item">
                                            <i class="layui-icon layui-timeline-axis"></i>
                                            <div class="layui-timeline-content">
                                                <h4 class="layui-timeline-title">定金已确认</h4>
                                                <p><time>25/07/2019</time> - Robert</p>
                                            </div>
                                        </li>

                                        <li class="layui-timeline-item">
                                            <i class="layui-icon layui-timeline-axis"></i>
                                            <div class="layui-timeline-content">
                                                <h4 class="layui-timeline-title">下单生产</h4>
                                                <p><time>25/07/2019</time> - Robert</p>
                                            </div>
                                        </li>

                                        <li class="layui-timeline-item">
                                            <i class="layui-icon layui-timeline-axis"></i>
                                            <div class="layui-timeline-content">
                                                <h4 class="layui-timeline-title">支付尾款</h4>
                                                <p><time>25/07/2019</time> - Robert</p>
                                            </div>
                                        </li>

                                        <li class="layui-timeline-item">
                                            <i class="layui-icon layui-timeline-axis"></i>
                                            <div class="layui-timeline-content">
                                                <h4 class="layui-timeline-title">尾款确认</h4>
                                                <p><time>25/07/2019</time> - Robert</p>
                                            </div>
                                        </li>

                                        <li class="layui-timeline-item">
                                            <i class="layui-icon layui-timeline-axis"></i>
                                            <div class="layui-timeline-content">
                                                <h4 class="layui-timeline-title">工厂提货</h4>
                                                <p><time>25/07/2019</time> - Robert</p>
                                            </div>
                                        </li>

                                        <li class="layui-timeline-item">
                                            <i class="layui-icon layui-timeline-axis"></i>
                                            <div class="layui-timeline-content">
                                                <h4 class="layui-timeline-title">报关</h4>
                                                <p><time>25/07/2019</time> - Robert</p>
                                            </div>
                                        </li>

                                        <li class="layui-timeline-item">
                                            <i class="layui-icon layui-timeline-axis"></i>
                                            <div class="layui-timeline-content">
                                                <h4 class="layui-timeline-title">支付税金</h4>
                                                <p><time>25/07/2019</time> - Robert</p>
                                            </div>
                                        </li>

                                        <li class="layui-timeline-item">
                                            <i class="layui-icon layui-timeline-axis"></i>
                                            <div class="layui-timeline-content">
                                                <h4 class="layui-timeline-title">税金收款确认</h4>
                                                <p><time>25/07/2019</time> - Robert</p>
                                            </div>
                                        </li>

                                        <li class="layui-timeline-item">
                                            <i class="layui-icon layui-timeline-axis"></i>
                                            <div class="layui-timeline-content">
                                                <h4 class="layui-timeline-title">入库</h4>
                                                <p><time>25/07/2019</time> - Robert</p>
                                            </div>
                                        </li>

                                    </ul>
                                </div>

                            </div>
                        </div>
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
    }).use(['index', 'workflow']);
</script>

<?php $this->endBlock(); ?>