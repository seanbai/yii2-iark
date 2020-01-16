
<div class="layui-fluid">
    <div class="layui-row layui-col-space5">
        <div class="layui-col-md6">
            <div class="layui-card" id="leftCard">
                <div class="layui-card-header">
                    Workflow Pending
                </div>
                <div class="layui-card-body">
                    <!-- table black -->
                    <table id="order" lay-filter="order"></table>
                    <!-- tool bar -->
                    <script type="text/html" id="toolbar">
                        <div class="layui-btn-container">
                            <button class="layui-btn layui-btn-sm layui-btn-normal" lay-event="status">改变状态</button>
                        </div>
                    </script>

                    <script type="text/html" id="editTool">
                        <a class="layui-btn layui-btn-xs layui-btn-primary" lay-event="edit">指派 / 查看</a>
                    </script>

                </div>
            </div>
        </div>

        <!-- -->
        <div class="layui-col-md6">
            <div class="layui-card" id="rightCard">
                <div class="layui-card-header">
                    Order Details - #GZL2018101800004
                </div>
                <div class="layui-card-body">
                    <div class="layui-tab layui-tab-brief">
                        <ul class="layui-tab-title">
                            <li class="layui-this">Overview</li>
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
                            <!-- timeline -->
                            <div class="layui-tab-item">
                                <ul class="layui-timeline">
                                    <li class="layui-timeline-item">
                                        <i class="layui-icon layui-timeline-axis"></i>
                                        <div class="layui-timeline-content">
                                            <h4 class="layui-timeline-title">报价</h4>
                                            <p>
                                                <time>25/07/2019</time>
                                                - Robert
                                            </p>
                                        </div>
                                    </li>

                                    <li class="layui-timeline-item">
                                        <i class="layui-icon layui-timeline-axis"></i>
                                        <div class="layui-timeline-content">
                                            <h4 class="layui-timeline-title">确认报价</h4>
                                            <p>
                                                <time>25/07/2019</time>
                                                - Robert
                                            </p>
                                        </div>
                                    </li>

                                    <li class="layui-timeline-item">
                                        <i class="layui-icon layui-timeline-axis"></i>
                                        <div class="layui-timeline-content">
                                            <h4 class="layui-timeline-title">同意报价并支付定金</h4>
                                            <p>
                                                <time>25/07/2019</time>
                                                - Robert
                                            </p>
                                        </div>
                                    </li>

                                    <li class="layui-timeline-item">
                                        <i class="layui-icon layui-timeline-axis"></i>
                                        <div class="layui-timeline-content">
                                            <h4 class="layui-timeline-title">定金已确认</h4>
                                            <p>
                                                <time>25/07/2019</time>
                                                - Robert
                                            </p>
                                        </div>
                                    </li>

                                    <li class="layui-timeline-item">
                                        <i class="layui-icon layui-timeline-axis"></i>
                                        <div class="layui-timeline-content">
                                            <h4 class="layui-timeline-title">下单生产</h4>
                                            <p>
                                                <time>25/07/2019</time>
                                                - Robert
                                            </p>
                                        </div>
                                    </li>

                                    <li class="layui-timeline-item">
                                        <i class="layui-icon layui-timeline-axis"></i>
                                        <div class="layui-timeline-content">
                                            <h4 class="layui-timeline-title">支付尾款</h4>
                                            <p>
                                                <time>25/07/2019</time>
                                                - Robert
                                            </p>
                                        </div>
                                    </li>

                                    <li class="layui-timeline-item">
                                        <i class="layui-icon layui-timeline-axis"></i>
                                        <div class="layui-timeline-content">
                                            <h4 class="layui-timeline-title">尾款确认</h4>
                                            <p>
                                                <time>25/07/2019</time>
                                                - Robert
                                            </p>
                                        </div>
                                    </li>

                                    <li class="layui-timeline-item">
                                        <i class="layui-icon layui-timeline-axis"></i>
                                        <div class="layui-timeline-content">
                                            <h4 class="layui-timeline-title">工厂提货</h4>
                                            <p>
                                                <time>25/07/2019</time>
                                                - Robert
                                            </p>
                                        </div>
                                    </li>

                                    <li class="layui-timeline-item">
                                        <i class="layui-icon layui-timeline-axis"></i>
                                        <div class="layui-timeline-content">
                                            <h4 class="layui-timeline-title">报关</h4>
                                            <p>
                                                <time>25/07/2019</time>
                                                - Robert
                                            </p>
                                        </div>
                                    </li>

                                    <li class="layui-timeline-item">
                                        <i class="layui-icon layui-timeline-axis"></i>
                                        <div class="layui-timeline-content">
                                            <h4 class="layui-timeline-title">支付税金</h4>
                                            <p>
                                                <time>25/07/2019</time>
                                                - Robert
                                            </p>
                                        </div>
                                    </li>

                                    <li class="layui-timeline-item">
                                        <i class="layui-icon layui-timeline-axis"></i>
                                        <div class="layui-timeline-content">
                                            <h4 class="layui-timeline-title">税金收款确认</h4>
                                            <p>
                                                <time>25/07/2019</time>
                                                - Robert
                                            </p>
                                        </div>
                                    </li>

                                    <li class="layui-timeline-item">
                                        <i class="layui-icon layui-timeline-axis"></i>
                                        <div class="layui-timeline-content">
                                            <h4 class="layui-timeline-title">入库</h4>
                                            <p>
                                                <time>25/07/2019</time>
                                                - Robert
                                            </p>
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

<

<script src="/public/admin/ui/layui.js"></script>
<script>
    layui.config({
        base: '/public/admin/' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['index', 'workflow']);
</script>

<!-- 数据表格状态格式化 -->
<script type="text/html" id="orderStatus">
    {{#  if(d.order_status == 1){ }}
    <span class="tag layui-bg-red">新订单</span>
    {{#  }else if(d.order_status == 2){ }}
    <span class="tag layui-bg-red">等待指派</span>
    {{#  }else if(d.order_status == 3){ }}
    <span class="tag layui-bg-cyan">等待供货商报价</span>
    {{#  }else if(d.order_status == 4){ }}
    <span class="tag layui-bg-red">供货商已报价</span>
    {{#  }else if(d.order_status == 5){ }}
    <span class="tag layui-bg-cyan">报价已发送</span>
    {{#  }else if(d.order_status == 6){ }}
    <span class="tag layui-bg-cyan">报价已确认</span>
    {{#  }else if(d.order_status == 7){ }}
    <span class="tag layui-bg-red">定金待收取</span>
    {{#  }else if(d.order_status == 8){ }}
    <span class="tag layui-bg-red">定金待确认</span>
    {{#  }else if(d.order_status == 9){ }}
    <span class="tag layui-bg-cyan">供货商定金已支付</span>
    {{#  }else if(d.order_status == 10){ }}
    <span class="tag layui-bg-cyan">生产中</span>
    {{#  }else if(d.order_status == 11){ }}
    <span class="tag layui-bg-red">尾款待支付</span>
    {{#  }else if(d.order_status == 12){ }}
    <span class="tag layui-bg-red">采购方尾款发起</span>
    {{#  }else if(d.order_status == 13){ }}
    <span class="tag layui-bg-red">尾款已支付</span>
    {{#  }else if(d.order_status == 14){ }}
    <span class="tag layui-bg-red">供货商尾款已支付</span>
    {{#  }else if(d.order_status == 15){ }}
    <span class="tag layui-bg-red">待提货</span>
    {{#  }else if(d.order_status == 16){ }}
    <span class="tag layui-bg-red">报关中</span>
    {{#  }else if(d.order_status == 17){ }}
    <span class="tag layui-bg-cyan">税金发起</span>
    {{#  }else if(d.order_status == 18){ }}
    <span class="tag layui-bg-red">税金待确认</span>
    {{#  }else if(d.order_status == 19){ }}
    <span class="tag layui-bg-red">等待入库</span>
    {{#  }else if(d.order_status == 20){ }}
    <span class="tag layui-bg-red">待发货</span>
    {{#  }else if(d.order_status == 21){ }}
    <span class="tag layui-bg-cyan">物流运输中</span>
    {{# }else { }}
    <span class="tag layui-bg-red">已入库</span>
    {{# } }}
</script>


<!-- 产品清单弹窗，一定要放在 body 外层 -->
<div class="popList" style="display:none">
    <div class="layui-card">
        <div class="layui-card-body">
            <table id="items" lay-filter="items"></table>
        </div>
    </div>
</div>

<!-- 供货商下拉列表 -->
<div class="dropList" style="display:none">
    <div class="layui-card">
        <div class="layui-card-body">
            <select id="manuList">
                <option value="0">请邀请一家供货商进行报价</option>
            </select>
        </div>
    </div>
</div>

