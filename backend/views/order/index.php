<?php

use yii\helpers\Url;

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge,chrome=1">
  <link rel="stylesheet" href="<?= Url::to('@web/public/admin/ui/css/layui.css',true)?>">
  <link rel="stylesheet" href="<?= Url::to('@web/public/admin/css/custom.css',true)?>">
</head>
<body class="dark">

<div class="layui-fluid">
  <div class="layui-row layui-col-space15">
    <div class="layui-col-md12">
      <div class="layui-card">
        <div class="layui-card-header">Pending Order</div>
        <div class="layui-card-body">
          <table id="order" lay-filter="order"></table>
          <!-- tool bar -->
          <script type="text/html" id="pendingOrderBar">
            <div class="layui-btn-container">
                <button class="layui-btn layui-btn-sm layui-btn-normal" lay-event="details">查看产品清单</button>
                <button class="layui-btn layui-btn-sm layui-btn-normal" lay-event="cancel">取消采购</button>
                <button style="display: none" class="layui-btn layui-btn-sm layui-btn-normal" lay-event="status">订单状态变更</button>
                <button class="layui-btn layui-btn-sm layui-btn-normal" lay-event="goodslist">提货清单</button>
            </div>
          </script>
        </div>
      </div>
    </div>
  </div>
</div>


<script src="<?= Url::to('@web/public/admin/ui/layui.js',true)?>"></script>
<script>
  layui.config({
    base: '/public/admin/' //静态资源所在路径
  }).extend({
    index: 'lib/index' //主入口模块
  }).use(['index', 'purchase']);
</script>


<!-- 数据表格状态格式化 -->
<script type="text/html" id="orderStatus">
  {{#  if(d.status == 1){ }}
    <span class="tag layui-bg-cyan">等待处理</span>
  {{#  }else if(d.status == 2){ }}
    <span class="tag layui-bg-cyan">订单已确认</span>
  {{#  }else if(d.status == 3){ }}
    <span class="tag layui-bg-cyan">等待报价</span>
  {{#  }else if(d.status == 4){ }}
    <span class="tag layui-bg-cyan">等待报价</span>
  {{#  }else if(d.status == 5){ }}
    <span class="tag layui-bg-red">报价待确认</span>
  {{#  }else if(d.status == 6){ }}
    <span class="tag layui-bg-cyan">报价已确认</span>
  {{#  }else if(d.status == 7){ }}
    <span class="tag layui-bg-red">定金待收取</span>
  {{#  }else if(d.status == 8){ }}
    <span class="tag layui-bg-cyan">定金待确认</span>
  {{#  }else if(d.status == 9){ }}
    <span class="tag layui-bg-cyan">定金已确认</span>
  {{#  }else if(d.status == 10){ }}
    <span class="tag layui-bg-cyan">生产中</span>
  {{#  }else if(d.status == 11){ }}
    <span class="tag layui-bg-cyan">生产中</span>
  {{#  }else if(d.status == 12){ }}
    <span class="tag layui-bg-red">尾款待支付</span>
  {{#  }else if(d.status == 13){ }}
    <span class="tag layui-bg-cyan">尾款已支付</span>
  {{#  }else if(d.status == 14){ }}
    <span class="tag layui-bg-cyan">货物处理中</span>
  {{#  }else if(d.status == 15){ }}
    <span class="tag layui-bg-cyan">货物处理中</span>
  {{#  }else if(d.status == 16){ }}
    <span class="tag layui-bg-cyan">报关</span>
  {{#  }else if(d.status == 17){ }}
    <span class="tag layui-bg-red">税金待支付</span>
  {{#  }else if(d.status == 18){ }}
    <span class="tag layui-bg-cyan">税金已支付</span>
  {{#  }else if(d.status == 19){ }}
    <span class="tag layui-bg-cyan">国际物流运输中</span>
  {{#  }else if(d.status == 20){ }}
    <span class="tag layui-bg-cyan">入库等待发货</span>
  {{#  }else if(d.status == 21){ }}
    <span class="tag layui-bg-cyan">物流运输中</span>
  {{# }else { }}
    <span class="tag layui-bg-cyan">订单已完成</span>
  {{# } }}
</script>
</body>
</html>

<!-- 产品清单 -->
<div style="display:none" id="showItems" class="layui-fluid">
  <div class="layui-row layui-col-space10">
    <div class="layui-col-md12">
      <table class="items" id="items" lay-filter="items"></table>
        <!-- 行编辑 -->
        <script type="text/html" id="taxAction">
            <a class="layui-btn layui-btn-xs layui-bg-blue" lay-event="confirmTax">税金已支付</a>
            <a class="layui-btn layui-btn-xs layui-bg-blue" lay-event="confirmSupprot">服务费已支付</a>
        </script>
    </div>
    <!-- 顶部工具栏 -->
    <script type="text/html" id="showItemsBar">
    </script>
  </div>
</div>
