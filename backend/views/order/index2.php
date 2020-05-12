
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">Pending Order</div>
                <div class="layui-card-body">
                    <table id="order" lay-filter="order"></table>
                    <!-- tool bar -->
                    <script type="text/html" id="toolbarDemo">
                        <div class="layui-btn-container">
                            <button class="layui-btn layui-btn-sm layui-btn-normal" lay-event="details">查看产品清单</button>
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
    }).use(['index', 'myorder2']);
</script>


<!-- 数据表格状态格式化 -->
<script type="text/html" id="orderStatus">
    {{#  if(d.order_status == 1){ }}
    <span class="tag layui-bg-cyan">等待处理</span>
    {{#  }else if(d.order_status == 2){ }}
    <span class="tag layui-bg-cyan">订单已确认</span>
    {{#  }else if(d.order_status == 3){ }}
    <span class="tag layui-bg-cyan">等待报价</span>
    {{#  }else if(d.order_status == 4){ }}
    <span class="tag layui-bg-cyan">等待报价</span>
    {{#  }else if(d.order_status == 5){ }}
    <span class="tag layui-bg-red">报价待确认</span>
    {{#  }else if(d.order_status == 6){ }}
    <span class="tag layui-bg-cyan">报价已确认</span>
    {{#  }else if(d.order_status == 7){ }}
    <span class="tag layui-bg-red">定金待收取</span>
    {{#  }else if(d.order_status == 8){ }}
    <span class="tag layui-bg-cyan">定金已确认</span>
    {{#  }else if(d.order_status == 9){ }}
    <span class="tag layui-bg-cyan">定金已确认</span>
    {{#  }else if(d.order_status == 10){ }}
    <span class="tag layui-bg-cyan">生产中</span>
    {{#  }else if(d.order_status == 11){ }}
    <span class="tag layui-bg-cyan">生产中</span>
    {{#  }else if(d.order_status == 12){ }}
    <span class="tag layui-bg-red">生产中</span>
    {{#  }else if(d.order_status == 13){ }}
    <span class="tag layui-bg-cyan">尾款待支付</span>
    {{#  }else if(d.order_status == 14){ }}
    <span class="tag layui-bg-cyan">尾款支付完成</span>
    {{#  }else if(d.order_status == 15){ }}
    <span class="tag layui-bg-cyan">货物处理中</span>
    {{#  }else if(d.order_status == 16){ }}
    <span class="tag layui-bg-cyan">报关</span>
    {{#  }else if(d.order_status == 17){ }}
    <span class="tag layui-bg-red">税金待支付</span>
    {{#  }else if(d.order_status == 18){ }}
    <span class="tag layui-bg-cyan">税金已支付</span>
    {{#  }else if(d.order_status == 19){ }}
    <span class="tag layui-bg-cyan">国际物流运输中</span>
    {{#  }else if(d.order_status == 20){ }}
    <span class="tag layui-bg-cyan">入库等待发货</span>
    {{#  }else if(d.order_status == 21){ }}
    <span class="tag layui-bg-cyan">物流运输中</span>
    {{#  }else if(d.order_status == 22){ }}
    <span class="tag layui-bg-cyan">订单已完成</span>
    {{#  }else if(d.order_status == 23){ }}
    <span class="tag layui-bg-cyan">报价已拒绝</span>
    {{#  }else if(d.order_status == 0){ }}
    <span class="tag layui-bg-cyan">订单已取消</span>
    {{# }else { }}
    <span class="tag layui-bg-cyan">订单状态异常</span>
    {{# } }}
</script>