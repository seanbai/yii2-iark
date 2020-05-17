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
        <div class="layui-card-header">Waiting For Pay Merchant</div>
        <div class="layui-card-body">
          <table id="quote" lay-filter="quote"></table>
          <!-- tool bar -->
          <script type="text/html" id="quoteBar">
            <div class="layui-btn-container">
              <button class="layui-btn layui-btn-sm layui-btn-normal" lay-event="details">查看商品清单</button>
            </div>
          </script>
          <!-- row action -->
          <script type="text/html" id="action">
            <a class="layui-btn layui-btn-xs" lay-event="confirm">付款确认</a>
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
}).use(['index', 'pay']);
</script>

<!-- 数据表格状态格式化 -->
<script type="text/html" id="orderStatus">
  {{#  if(d.status == 1){ }}
    <span class="tag layui-bg-cyan">供货商定金待支付</span>
  {{# }else { }}
    <span class="tag layui-bg-red">供货商尾款待支付</span>
  {{# } }}
</script>

</body>
</html>

<!-- 产品清单 -->
<div style="display:none" id="showItems" class="layui-fluid">
  <div class="layui-row layui-col-space10">
    <div class="layui-col-md12">
      <table class="items" id="items" lay-filter="items"></table>
    </div>
  </div>
</div>

<!-- 产品清单 -->
<div style="display:none" id="subOrderBox" class="layui-fluid">
  <div class="layui-row layui-col-space10">
    <div class="layui-col-md12">
      <table id="subOrder" lay-filter="subOrder"></table>
      <!-- row action -->
      <script type="text/html" id="logPay">
        <a class="layui-btn layui-btn-xs" lay-event="logPay">登记付款</a>
      </script>
    </div>
  </div>
</div>

<!-- 确认收款表单 -->
<div style="display:none" id="confirmPay" class="layui-fluid">
  <div class="layui-row layui-col-space10">
    <div class="layui-col-md12">
      <form class="layui-form layui-form-pane">
        <!-- 自动计算总金额的50% -->
        <div class="layui-form-item">
          <label class="layui-form-label">订单总价</label>
          <div class="layui-input-block">
            <input type="text" name="total" value="20000" class="layui-input" disabled>
          </div>
        </div>
        <!-- 实际收款金额 -->
        <div class="layui-form-item">
          <label class="layui-form-label">实付定金</label>
          <div class="layui-input-block">
            <input type="text" name="deposit" lay-verify="required" autocomplete="off" class="layui-input">
          </div>
        </div>
        <!-- 实际收款金额 -->
        <div class="layui-form-item">
          <label class="layui-form-label">实付尾款</label>
          <div class="layui-input-block">
            <input type="text" name="balance" lay-verify="required" autocomplete="off" class="layui-input">
          </div>
        </div>
        <!-- 备注说明 -->
        <div class="layui-form-item layui-form-text">
          <label class="layui-form-label">付款备注</label>
          <div class="layui-input-block">
            <textarea name="info" class="layui-textarea"></textarea>
          </div>
        </div>
        <!-- 提交按钮 -->
        <div class="layui-form-item layui-form-text">
          <input type="text" name="subOrderId" id="orderId" hidden>
          <button type="submit" class="layui-btn" lay-submit="" lay-filter="confirmPay">确认付款</button>
        </div>
      </form>
    </div>
  </div>
</div>
